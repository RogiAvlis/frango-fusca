<?php
namespace FrangoFusca\Models;

class Cliente extends AbstractModel {
    protected static string $table = 'cliente';
    protected static bool $useEstabelecimento = true;

    public static function salvar(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE cliente 
                SET nome = :nome, telefone = :telefone, email = :email, endereco = :endereco, alterado_por = :alterado_por 
                WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
            ");
            return $stmt->execute([
                ':nome' => trim($dados['nome']),
                ':telefone' => trim($dados['telefone'] ?? ''),
                ':email' => trim($dados['email'] ?? ''),
                ':endereco' => trim($dados['endereco'] ?? ''),
                ':alterado_por' => $usuarioId,
                ':id' => $id,
                ':estabelecimento_id' => $estabelecimentoId
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO cliente (estabelecimento_id, nome, telefone, email, endereco, criado_por) 
                VALUES (:estabelecimento_id, :nome, :telefone, :email, :endereco, :criado_por)
            ");
            return $stmt->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':nome' => trim($dados['nome']),
                ':telefone' => trim($dados['telefone'] ?? ''),
                ':email' => trim($dados['email'] ?? ''),
                ':endereco' => trim($dados['endereco'] ?? ''),
                ':criado_por' => $usuarioId
            ]);
        }
    }
}
