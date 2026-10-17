<?php
namespace FrangoFusca\Models;

class Banco extends AbstractModel {
    protected static string $table = 'banco';
    protected static bool $useEstabelecimento = false;

    public static function salvar(array $dados, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE banco 
                SET codigo = :codigo, nome = :nome, alterado_por = :alterado_por 
                WHERE id = :id AND status_registro = 1
            ");
            return $stmt->execute([
                ':codigo' => trim($dados['codigo'] ?? ''),
                ':nome' => trim($dados['nome']),
                ':alterado_por' => $usuarioId,
                ':id' => $id
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO banco (codigo, nome, criado_por) 
                VALUES (:codigo, :nome, :criado_por)
            ");
            return $stmt->execute([
                ':codigo' => trim($dados['codigo'] ?? ''),
                ':nome' => trim($dados['nome']),
                ':criado_por' => $usuarioId
            ]);
        }
    }
}
