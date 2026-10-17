<?php
namespace FrangoFusca\Models;

class UnidadeMedida extends AbstractModel {
    protected static string $table = 'unidade_medida';
    protected static bool $useEstabelecimento = false;

    public static function salvar(array $dados, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE unidade_medida 
                SET sigla = :sigla, nome = :nome, alterado_por = :alterado_por 
                WHERE id = :id AND status_registro = 1
            ");
            return $stmt->execute([
                ':sigla' => strtoupper(trim($dados['sigla'])),
                ':nome' => trim($dados['nome']),
                ':alterado_por' => $usuarioId,
                ':id' => $id
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO unidade_medida (sigla, nome, criado_por) 
                VALUES (:sigla, :nome, :criado_por)
            ");
            return $stmt->execute([
                ':sigla' => strtoupper(trim($dados['sigla'])),
                ':nome' => trim($dados['nome']),
                ':criado_por' => $usuarioId
            ]);
        }
    }
}
