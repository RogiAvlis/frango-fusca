<?php
namespace FrangoFusca\Models;

class AmbienteVenda extends AbstractModel {
    protected static string $table = 'ambiente_venda';
    protected static bool $useEstabelecimento = true;

    public static function salvar(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;
        $taxa = !empty($dados['taxa']) ? (float)str_replace(',', '.', $dados['taxa']) : 0.00;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE ambiente_venda 
                SET nome = :nome, descricao = :descricao, taxa = :taxa, alterado_por = :alterado_por 
                WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
            ");
            return $stmt->execute([
                ':nome' => trim($dados['nome']),
                ':descricao' => trim($dados['descricao'] ?? ''),
                ':taxa' => $taxa,
                ':alterado_por' => $usuarioId,
                ':id' => $id,
                ':estabelecimento_id' => $estabelecimentoId
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO ambiente_venda (estabelecimento_id, nome, descricao, taxa, criado_por) 
                VALUES (:estabelecimento_id, :nome, :descricao, :taxa, :criado_por)
            ");
            return $stmt->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':nome' => trim($dados['nome']),
                ':descricao' => trim($dados['descricao'] ?? ''),
                ':taxa' => $taxa,
                ':criado_por' => $usuarioId
            ]);
        }
    }
}
