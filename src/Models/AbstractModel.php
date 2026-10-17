<?php
namespace FrangoFusca\Models;

use FrangoFusca\Database\Connection;
use PDO;

abstract class AbstractModel {
    protected static string $table = '';
    protected static bool $useEstabelecimento = true;

    protected static function getDb(): PDO {
        return Connection::getInstance();
    }

    public static function listarTodos(?int $estabelecimentoId = null): array {
        $db = static::getDb();
        $sql = "SELECT * FROM `" . static::$table . "` WHERE status_registro = 1";
        $params = [];

        if (static::$useEstabelecimento && $estabelecimentoId) {
            $sql .= " AND estabelecimento_id = :estabelecimento_id";
            $params[':estabelecimento_id'] = $estabelecimentoId;
        }

        $sql .= " ORDER BY id DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function buscarPorId(int $id, ?int $estabelecimentoId = null): ?array {
        $db = static::getDb();
        $sql = "SELECT * FROM `" . static::$table . "` WHERE id = :id AND status_registro = 1";
        $params = [':id' => $id];

        if (static::$useEstabelecimento && $estabelecimentoId) {
            $sql .= " AND estabelecimento_id = :estabelecimento_id";
            $params[':estabelecimento_id'] = $estabelecimentoId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function deletar(int $id, int $usuarioId, ?int $estabelecimentoId = null): bool {
        $db = static::getDb();
        $sql = "UPDATE `" . static::$table . "` SET status_registro = 0, alterado_por = :alterado_por WHERE id = :id";
        $params = [
            ':id' => $id,
            ':alterado_por' => $usuarioId
        ];

        if (static::$useEstabelecimento && $estabelecimentoId) {
            $sql .= " AND estabelecimento_id = :estabelecimento_id";
            $params[':estabelecimento_id'] = $estabelecimentoId;
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }
}
