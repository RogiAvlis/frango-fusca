<?php
namespace FrangoFusca\Models;

class MetodoPagamento extends AbstractModel {
    protected static string $table = 'metodo_pagamento';
    protected static bool $useEstabelecimento = true;

    public static function listarTodos(?int $estabelecimentoId = null): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT m.*, b.nome as banco_nome 
            FROM metodo_pagamento m
            LEFT JOIN banco b ON b.id = m.banco_id
            WHERE m.status_registro = 1 AND m.estabelecimento_id = :estabelecimento_id
            ORDER BY m.id DESC
        ");
        $stmt->execute([':estabelecimento_id' => $estabelecimentoId]);
        return $stmt->fetchAll();
    }

    public static function salvar(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;
        $bancoId = !empty($dados['banco_id']) ? (int)$dados['banco_id'] : null;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE metodo_pagamento 
                SET nome = :nome, banco_id = :banco_id, agencia = :agencia, conta = :conta, alterado_por = :alterado_por 
                WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
            ");
            return $stmt->execute([
                ':nome' => trim($dados['nome']),
                ':banco_id' => $bancoId,
                ':agencia' => trim($dados['agencia'] ?? ''),
                ':conta' => trim($dados['conta'] ?? ''),
                ':alterado_por' => $usuarioId,
                ':id' => $id,
                ':estabelecimento_id' => $estabelecimentoId
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO metodo_pagamento (estabelecimento_id, nome, banco_id, agencia, conta, criado_por) 
                VALUES (:estabelecimento_id, :nome, :banco_id, :agencia, :conta, :criado_por)
            ");
            return $stmt->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':nome' => trim($dados['nome']),
                ':banco_id' => $bancoId,
                ':agencia' => trim($dados['agencia'] ?? ''),
                ':conta' => trim($dados['conta'] ?? ''),
                ':criado_por' => $usuarioId
            ]);
        }
    }
}
