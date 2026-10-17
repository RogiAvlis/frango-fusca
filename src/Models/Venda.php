<?php
namespace FrangoFusca\Models;

use FrangoFusca\Database\Connection;

class Venda extends AbstractModel {
    protected static string $table = 'venda';
    protected static bool $useEstabelecimento = true;

    public static function listarVendas(int $estabelecimentoId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT v.*, c.nome as cliente_nome, u.nome as vendedor_nome, a.nome as canal_nome, m.nome as metodo_nome, mq.nome as maquina_nome
            FROM venda v
            LEFT JOIN cliente c ON c.id = v.cliente_id
            INNER JOIN usuario u ON u.id = v.vendedor_id
            INNER JOIN ambiente_venda a ON a.id = v.ambiente_venda_id
            INNER JOIN metodo_pagamento m ON m.id = v.metodo_pagamento_id
            LEFT JOIN maquina_venda mq ON mq.id = v.maquina_id
            WHERE v.status_registro = 1 AND v.estabelecimento_id = :estabelecimento_id
            ORDER BY v.data_venda DESC
        ");
        $stmt->execute([':estabelecimento_id' => $estabelecimentoId]);
        return $stmt->fetchAll();
    }

    public static function registrarVenda(array $dados, int $estabelecimentoId, int $vendedorId): bool {
        $db = Connection::getInstance();

        $clienteId = !empty($dados['cliente_id']) ? (int)$dados['cliente_id'] : null;
        $ambienteVendaId = (int)$dados['ambiente_venda_id'];
        $metodoPagamentoId = (int)$dados['metodo_pagamento_id'];
        $maquinaId = !empty($dados['maquina_id']) ? (int)$dados['maquina_id'] : null;
        $itens = $dados['itens'] ?? [];

        if (empty($itens)) {
            return false;
        }

        // Buscar taxas do canal e da maquininha
        $stmtCanal = $db->prepare("SELECT taxa FROM ambiente_venda WHERE id = :id LIMIT 1");
        $stmtCanal->execute([':id' => $ambienteVendaId]);
        $taxaCanalPct = (float)($stmtCanal->fetch()['taxa'] ?? 0);

        $taxaMaqPct = 0;
        if ($maquinaId) {
            $stmtMaq = $db->prepare("SELECT taxa FROM maquina_venda WHERE id = :id LIMIT 1");
            $stmtMaq->execute([':id' => $maquinaId]);
            $taxaMaqPct = (float)($stmtMaq->fetch()['taxa'] ?? 0);
        }

        $valorTotal = 0;
        foreach ($itens as $item) {
            $valorTotal += ((float)$item['preco_venda'] * (float)$item['quantidade']);
        }

        $taxaCanalAplicada = $valorTotal * ($taxaCanalPct / 100);
        $taxaMaquinaAplicada = $valorTotal * ($taxaMaqPct / 100);
        $valorLiquido = $valorTotal - $taxaCanalAplicada - $taxaMaquinaAplicada;

        $db->beginTransaction();
        try {
            // 1. Inserir cabeçalho da Venda
            $stmtVenda = $db->prepare("
                INSERT INTO venda (estabelecimento_id, cliente_id, vendedor_id, ambiente_venda_id, metodo_pagamento_id, maquina_id, valor_total, taxa_canal_aplicada, taxa_maquina_aplicada, valor_liquido, criado_por)
                VALUES (:estabelecimento_id, :cliente_id, :vendedor_id, :ambiente_venda_id, :metodo_pagamento_id, :maquina_id, :valor_total, :taxa_canal_aplicada, :taxa_maquina_aplicada, :valor_liquido, :criado_por)
            ");
            $stmtVenda->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':cliente_id' => $clienteId,
                ':vendedor_id' => $vendedorId,
                ':ambiente_venda_id' => $ambienteVendaId,
                ':metodo_pagamento_id' => $metodoPagamentoId,
                ':maquina_id' => $maquinaId,
                ':valor_total' => $valorTotal,
                ':taxa_canal_aplicada' => $taxaCanalAplicada,
                ':taxa_maquina_aplicada' => $taxaMaquinaAplicada,
                ':valor_liquido' => $valorLiquido,
                ':criado_por' => $vendedorId
            ]);
            $vendaId = (int)$db->lastInsertId();

            // 2. Inserir itens da venda e dar baixa automática no estoque
            $stmtItem = $db->prepare("
                INSERT INTO item_venda (venda_id, produto_id, quantidade, preco_venda, subtotal, criado_por)
                VALUES (:venda_id, :produto_id, :quantidade, :preco_venda, :subtotal, :criado_por)
            ");

            $stmtBaixa = $db->prepare("
                UPDATE produto 
                SET quantidade_estoque = quantidade_estoque - :qtd 
                WHERE id = :id AND estabelecimento_id = :estabelecimento_id
            ");

            foreach ($itens as $item) {
                $prodId = (int)$item['produto_id'];
                $qtd = (float)$item['quantidade'];
                $prcVenda = (float)$item['preco_venda'];
                $subtotal = $qtd * $prcVenda;

                $stmtItem->execute([
                    ':venda_id' => $vendaId,
                    ':produto_id' => $prodId,
                    ':quantidade' => $qtd,
                    ':preco_venda' => $prcVenda,
                    ':subtotal' => $subtotal,
                    ':criado_por' => $vendedorId
                ]);

                // Verificar se o produto tem receita (ficha técnica)
                $stmtRec = $db->prepare("SELECT produto_ingrediente_id, quantidade_necessaria FROM receita WHERE produto_principal_id = :id");
                $stmtRec->execute([':id' => $prodId]);
                $ingredientes = $stmtRec->fetchAll();

                if (!empty($ingredientes)) {
                    // Dar baixa nos ingredientes da receita
                    foreach ($ingredientes as $ing) {
                        $qtdBaixaIng = $qtd * (float)$ing['quantidade_necessaria'];
                        $stmtBaixa->execute([
                            ':qtd' => $qtdBaixaIng,
                            ':id' => $ing['produto_ingrediente_id'],
                            ':estabelecimento_id' => $estabelecimentoId
                        ]);
                    }
                } else {
                    // Dar baixa no próprio produto
                    $stmtBaixa->execute([
                        ':qtd' => $qtd,
                        ':id' => $prodId,
                        ':estabelecimento_id' => $estabelecimentoId
                    ]);
                }
            }

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
