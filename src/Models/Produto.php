<?php
namespace FrangoFusca\Models;

use FrangoFusca\Database\Connection;

class Produto extends AbstractModel {
    protected static string $table = 'produto';
    protected static bool $useEstabelecimento = true;

    public static function listarComDetalhes(int $estabelecimentoId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT p.*, u.sigla as unidade_sigla, u.nome as unidade_nome, f.nome as fornecedor_nome
            FROM produto p
            INNER JOIN unidade_medida u ON u.id = p.unidade_medida_id
            LEFT JOIN fornecedor f ON f.id = p.fornecedor_id
            WHERE p.status_registro = 1 AND p.estabelecimento_id = :estabelecimento_id
            ORDER BY p.nome ASC
        ");
        $stmt->execute([':estabelecimento_id' => $estabelecimentoId]);
        return $stmt->fetchAll();
    }

    public static function buscarLotesEstoque(int $produtoId, int $estabelecimentoId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT e.*, f.nome as fornecedor_nome, m.nome as metodo_pagamento_nome, u.sigla as unidade_sigla
            FROM estoque e
            LEFT JOIN fornecedor f ON f.id = e.fornecedor_id
            LEFT JOIN metodo_pagamento m ON m.id = e.metodo_pagamento_id
            INNER JOIN unidade_medida u ON u.id = e.unidade_medida_id
            WHERE e.produto_id = :produto_id AND e.estabelecimento_id = :estabelecimento_id AND e.status_registro = 1
            ORDER BY e.data_entrada DESC
        ");
        $stmt->execute([
            ':produto_id' => $produtoId,
            ':estabelecimento_id' => $estabelecimentoId
        ]);
        return $stmt->fetchAll();
    }

    public static function buscarItensCombo(int $comboId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT pc.*, p.nome as produto_nome, p.preco_venda, u.sigla as unidade_sigla
            FROM produto_combo pc
            INNER JOIN produto p ON p.id = pc.item_produto_id
            INNER JOIN unidade_medida u ON u.id = p.unidade_medida_id
            WHERE pc.combo_id = :combo_id
        ");
        $stmt->execute([':combo_id' => $comboId]);
        return $stmt->fetchAll();
    }

    public static function salvar(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;

        $tipoProduto = $dados['tipo_produto'] ?? 'acabado';
        $nome = trim($dados['nome']);
        $descricao = trim($dados['descricao'] ?? '');
        $precoCusto = !empty($dados['preco_custo']) ? (float)str_replace(',', '.', $dados['preco_custo']) : 0.00;
        $precoVenda = !empty($dados['preco_venda']) ? (float)str_replace(',', '.', $dados['preco_venda']) : 0.00;
        $estoqueMinimo = !empty($dados['estoque_minimo']) ? (float)str_replace(',', '.', $dados['estoque_minimo']) : 5.000;
        $unidadeMedidaId = (int)($dados['unidade_medida_id'] ?? 1);
        $fornecedorId = !empty($dados['fornecedor_id']) ? (int)$dados['fornecedor_id'] : null;

        $db->beginTransaction();
        try {
            if ($id) {
                $stmt = $db->prepare("
                    UPDATE produto 
                    SET tipo_produto = :tipo_produto, nome = :nome, descricao = :descricao, 
                        preco_custo = :preco_custo, preco_venda = :preco_venda, estoque_minimo = :estoque_minimo, 
                        unidade_medida_id = :unidade_medida_id, fornecedor_id = :fornecedor_id, alterado_por = :alterado_por 
                    WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
                ");
                $stmt->execute([
                    ':tipo_produto' => $tipoProduto,
                    ':nome' => $nome,
                    ':descricao' => $descricao,
                    ':preco_custo' => $precoCusto,
                    ':preco_venda' => $precoVenda,
                    ':estoque_minimo' => $estoqueMinimo,
                    ':unidade_medida_id' => $unidadeMedidaId,
                    ':fornecedor_id' => $fornecedorId,
                    ':alterado_por' => $usuarioId,
                    ':id' => $id,
                    ':estabelecimento_id' => $estabelecimentoId
                ]);
            } else {
                $stmt = $db->prepare("
                    INSERT INTO produto (estabelecimento_id, tipo_produto, nome, descricao, preco_custo, preco_venda, quantidade_estoque, estoque_minimo, unidade_medida_id, fornecedor_id, criado_por) 
                    VALUES (:estabelecimento_id, :tipo_produto, :nome, :descricao, :preco_custo, :preco_venda, 0.000, :estoque_minimo, :unidade_medida_id, :fornecedor_id, :criado_por)
                ");
                $stmt->execute([
                    ':estabelecimento_id' => $estabelecimentoId,
                    ':tipo_produto' => $tipoProduto,
                    ':nome' => $nome,
                    ':descricao' => $descricao,
                    ':preco_custo' => $precoCusto,
                    ':preco_venda' => $precoVenda,
                    ':estoque_minimo' => $estoqueMinimo,
                    ':unidade_medida_id' => $unidadeMedidaId,
                    ':fornecedor_id' => $fornecedorId,
                    ':criado_por' => $usuarioId
                ]);
                $id = (int)$db->lastInsertId();
            }

            // Se for Combo, salvar itens do combo
            if ($tipoProduto === 'combo') {
                $db->prepare("DELETE FROM produto_combo WHERE combo_id = :combo_id")->execute([':combo_id' => $id]);
                if (isset($dados['combo_items']) && is_array($dados['combo_items'])) {
                    $stmtCombo = $db->prepare("
                        INSERT INTO produto_combo (combo_id, item_produto_id, quantidade, criado_por) 
                        VALUES (:combo_id, :item_produto_id, :quantidade, :criado_por)
                    ");
                    foreach ($dados['combo_items'] as $item) {
                        if (!empty($item['item_produto_id']) && !empty($item['quantidade'])) {
                            $stmtCombo->execute([
                                ':combo_id' => $id,
                                ':item_produto_id' => (int)$item['item_produto_id'],
                                ':quantidade' => (float)str_replace(',', '.', $item['quantidade']),
                                ':criado_por' => $usuarioId
                            ]);
                        }
                    }
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
