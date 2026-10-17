<?php
namespace FrangoFusca\Models;

use FrangoFusca\Database\Connection;

class Receita extends AbstractModel {
    protected static string $table = 'receita';
    protected static bool $useEstabelecimento = false;

    public static function buscarIngredientesDoProduto(int $produtoPrincipalId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT r.*, p.nome as ingrediente_nome, p.preco_custo as ingrediente_preco_custo, u.sigla as unidade_sigla
            FROM receita r
            INNER JOIN produto p ON p.id = r.produto_ingrediente_id
            INNER JOIN unidade_medida u ON u.id = r.unidade_medida_id
            WHERE r.produto_principal_id = :produto_principal_id
            ORDER BY p.nome ASC
        ");
        $stmt->execute([':produto_principal_id' => $produtoPrincipalId]);
        return $stmt->fetchAll();
    }

    public static function salvarReceitaOficial(int $produtoPrincipalId, array $ingredientes, int $usuarioId): bool {
        $db = Connection::getInstance();

        $db->beginTransaction();
        try {
            // Remover receita antiga se existir
            $stmtDel = $db->prepare("DELETE FROM receita WHERE produto_principal_id = :produto_principal_id");
            $stmtDel->execute([':produto_principal_id' => $produtoPrincipalId]);

            $stmtIns = $db->prepare("
                INSERT INTO receita (produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id, criado_por)
                VALUES (:produto_principal_id, :produto_ingrediente_id, :quantidade_necessaria, :unidade_medida_id, :criado_por)
            ");

            $custoTotalReceita = 0.00;

            foreach ($ingredientes as $item) {
                if (!empty($item['produto_ingrediente_id']) && !empty($item['quantidade_necessaria'])) {
                    $ingId = (int)$item['produto_ingrediente_id'];
                    $qtd = (float)str_replace(',', '.', $item['quantidade_necessaria']);
                    $umId = (int)($item['unidade_medida_id'] ?? 1);

                    $stmtIns->execute([
                        ':produto_principal_id' => $produtoPrincipalId,
                        ':produto_ingrediente_id' => $ingId,
                        ':quantidade_necessaria' => $qtd,
                        ':unidade_medida_id' => $umId,
                        ':criado_por' => $usuarioId
                    ]);

                    // Buscar preço de custo do ingrediente para atualizar o preço de custo total do produto principal
                    $stmtProd = $db->prepare("SELECT preco_custo FROM produto WHERE id = :id LIMIT 1");
                    $stmtProd->execute([':id' => $ingId]);
                    $ingData = $stmtProd->fetch();
                    if ($ingData) {
                        $custoTotalReceita += ($ingData['preco_custo'] * $qtd);
                    }
                }
            }

            // Atualizar preco_custo do produto principal com o custo da receita
            $stmtUpdateCusto = $db->prepare("UPDATE produto SET preco_custo = :preco_custo WHERE id = :id");
            $stmtUpdateCusto->execute([
                ':preco_custo' => $custoTotalReceita,
                ':id' => $produtoPrincipalId
            ]);

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
