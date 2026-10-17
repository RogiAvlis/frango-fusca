<?php
namespace FrangoFusca\Models;

class ReservaFinanceira extends AbstractModel {
    protected static string $table = 'reserva_financeira';
    protected static bool $useEstabelecimento = true;

    public static function salvar(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;

        $descricao = trim($dados['descricao']);
        $metaValor = !empty($dados['meta_valor']) ? (float)str_replace(',', '.', $dados['meta_valor']) : 0.00;
        $saldoAtual = !empty($dados['saldo_atual']) ? (float)str_replace(',', '.', $dados['saldo_atual']) : 0.00;
        $dataPrevisao = !empty($dados['data_previsao']) ? $dados['data_previsao'] : null;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE reserva_financeira 
                SET descricao = :descricao, meta_valor = :meta_valor, saldo_atual = :saldo_atual, data_previsao = :data_previsao, alterado_por = :alterado_por 
                WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
            ");
            return $stmt->execute([
                ':descricao' => $descricao,
                ':meta_valor' => $metaValor,
                ':saldo_atual' => $saldoAtual,
                ':data_previsao' => $dataPrevisao,
                ':alterado_por' => $usuarioId,
                ':id' => $id,
                ':estabelecimento_id' => $estabelecimentoId
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO reserva_financeira (estabelecimento_id, descricao, meta_valor, saldo_atual, data_previsao, criado_por) 
                VALUES (:estabelecimento_id, :descricao, :meta_valor, :saldo_atual, :data_previsao, :criado_por)
            ");
            return $stmt->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':descricao' => $descricao,
                ':meta_valor' => $metaValor,
                ':saldo_atual' => $saldoAtual,
                ':data_previsao' => $dataPrevisao,
                ':criado_por' => $usuarioId
            ]);
        }
    }

    public static function atualizarSaldo(int $id, float $valorAporte, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $stmt = $db->prepare("
            UPDATE reserva_financeira 
            SET saldo_atual = saldo_atual + :valor, alterado_por = :alterado_por 
            WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
        ");
        return $stmt->execute([
            ':valor' => $valorAporte,
            ':alterado_por' => $usuarioId,
            ':id' => $id,
            ':estabelecimento_id' => $estabelecimentoId
        ]);
    }
}
