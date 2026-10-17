<?php
namespace FrangoFusca\Models;

use FrangoFusca\Database\Connection;

class Estoque extends AbstractModel {
    protected static string $table = 'estoque';
    protected static bool $useEstabelecimento = true;

    public static function listarMovimentacoes(int $estabelecimentoId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT e.*, p.nome as produto_nome, u.sigla as unidade_sigla, f.nome as fornecedor_nome, m.nome as metodo_pagamento_nome
            FROM estoque e
            INNER JOIN produto p ON p.id = e.produto_id
            INNER JOIN unidade_medida u ON u.id = e.unidade_medida_id
            LEFT JOIN fornecedor f ON f.id = e.fornecedor_id
            LEFT JOIN metodo_pagamento m ON m.id = e.metodo_pagamento_id
            WHERE e.status_registro = 1 AND e.estabelecimento_id = :estabelecimento_id
            ORDER BY e.data_entrada DESC
        ");
        $stmt->execute([':estabelecimento_id' => $estabelecimentoId]);
        return $stmt->fetchAll();
    }

    public static function registrarEntrada(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();

        $produtoId = (int)$dados['produto_id'];
        $fornecedorId = !empty($dados['fornecedor_id']) ? (int)$dados['fornecedor_id'] : null;
        $metodoPagamentoId = !empty($dados['metodo_pagamento_id']) ? (int)$dados['metodo_pagamento_id'] : null;
        $unidadeMedidaId = (int)$dados['unidade_medida_id'];
        $quantidade = (float)str_replace(',', '.', $dados['quantidade']);
        $precoCustoUnitario = (float)str_replace(',', '.', $dados['preco_custo_unitario']);
        $valorTotal = $quantidade * $precoCustoUnitario;
        $observacoes = trim($dados['observacoes'] ?? '');

        $db->beginTransaction();
        try {
            // 1. Inserir lote na tabela estoque
            $stmtInsert = $db->prepare("
                INSERT INTO estoque (estabelecimento_id, produto_id, fornecedor_id, quantidade, unidade_medida_id, preco_custo_unitario, valor_total, metodo_pagamento_id, observacoes, criado_por)
                VALUES (:estabelecimento_id, :produto_id, :fornecedor_id, :quantidade, :unidade_medida_id, :preco_custo_unitario, :valor_total, :metodo_pagamento_id, :observacoes, :criado_por)
            ");
            $stmtInsert->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':produto_id' => $produtoId,
                ':fornecedor_id' => $fornecedorId,
                ':quantidade' => $quantidade,
                ':unidade_medida_id' => $unidadeMedidaId,
                ':preco_custo_unitario' => $precoCustoUnitario,
                ':valor_total' => $valorTotal,
                ':metodo_pagamento_id' => $metodoPagamentoId,
                ':observacoes' => $observacoes,
                ':criado_por' => $usuarioId
            ]);

            // 2. Incrementar quantidade no produto e atualizar preco_custo com o último valor de compra
            $stmtUpdateProduto = $db->prepare("
                UPDATE produto 
                SET quantidade_estoque = quantidade_estoque + :quantidade, 
                    preco_custo = :preco_custo_unitario,
                    alterado_por = :alterado_por
                WHERE id = :produto_id AND estabelecimento_id = :estabelecimento_id
            ");
            $stmtUpdateProduto->execute([
                ':quantidade' => $quantidade,
                ':preco_custo_unitario' => $precoCustoUnitario,
                ':alterado_por' => $usuarioId,
                ':produto_id' => $produtoId,
                ':estabelecimento_id' => $estabelecimentoId
            ]);

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }

    public static function sugestoesDeCompra(int $estabelecimentoId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT p.id, p.nome, p.tipo_produto, p.quantidade_estoque, p.estoque_minimo, p.preco_custo, 
                   u.sigla as unidade_sigla, f.nome as fornecedor_nome,
                   GREATEST(0, (p.estoque_minimo * 2) - p.quantidade_estoque) as quantidade_sugerida
            FROM produto p
            INNER JOIN unidade_medida u ON u.id = p.unidade_medida_id
            LEFT JOIN fornecedor f ON f.id = p.fornecedor_id
            WHERE p.status_registro = 1 AND p.estabelecimento_id = :estabelecimento_id
              AND p.quantidade_estoque <= p.estoque_minimo
            ORDER BY (p.estoque_minimo - p.quantidade_estoque) DESC
        ");
        $stmt->execute([':estabelecimento_id' => $estabelecimentoId]);
        return $stmt->fetchAll();
    }
}
