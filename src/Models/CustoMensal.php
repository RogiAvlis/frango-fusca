<?php
namespace FrangoFusca\Models;

class CustoMensal extends AbstractModel {
    protected static string $table = 'custo_mensal';
    protected static bool $useEstabelecimento = true;

    public static function listarComMetodo(int $estabelecimentoId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT c.*, m.nome as metodo_pagamento_nome
            FROM custo_mensal c
            LEFT JOIN metodo_pagamento m ON m.id = c.metodo_pagamento_id
            WHERE c.status_registro = 1 AND c.estabelecimento_id = :estabelecimento_id
            ORDER BY c.mes DESC, c.ano DESC, c.id DESC
        ");
        $stmt->execute([':estabelecimento_id' => $estabelecimentoId]);
        return $stmt->fetchAll();
    }

    public static function salvar(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;

        $descricao = trim($dados['descricao']);
        $tipoCusto = $dados['tipo_custo'] ?? 'fixo';
        $statusPagamento = $dados['status_pagamento'] ?? 'pendente';
        $valor = (float)str_replace(',', '.', $dados['valor']);
        $dataVencimento = !empty($dados['data_vencimento']) ? $dados['data_vencimento'] : null;
        $dataPagamento = !empty($dados['data_pagamento']) ? $dados['data_pagamento'] : null;
        $mes = (int)($dados['mes'] ?? date('n'));
        $ano = (int)($dados['ano'] ?? date('Y'));
        $metodoPagamentoId = !empty($dados['metodo_pagamento_id']) ? (int)$dados['metodo_pagamento_id'] : null;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE custo_mensal 
                SET descricao = :descricao, tipo_custo = :tipo_custo, status_pagamento = :status_pagamento, 
                    valor = :valor, data_vencimento = :data_vencimento, data_pagamento = :data_pagamento, 
                    mes = :mes, ano = :ano, metodo_pagamento_id = :metodo_pagamento_id, alterado_por = :alterado_por 
                WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
            ");
            return $stmt->execute([
                ':descricao' => $descricao,
                ':tipo_custo' => $tipoCusto,
                ':status_pagamento' => $statusPagamento,
                ':valor' => $valor,
                ':data_vencimento' => $dataVencimento,
                ':data_pagamento' => $dataPagamento,
                ':mes' => $mes,
                ':ano' => $ano,
                ':metodo_pagamento_id' => $metodoPagamentoId,
                ':alterado_por' => $usuarioId,
                ':id' => $id,
                ':estabelecimento_id' => $estabelecimentoId
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO custo_mensal (estabelecimento_id, descricao, tipo_custo, status_pagamento, valor, data_vencimento, data_pagamento, mes, ano, metodo_pagamento_id, criado_por) 
                VALUES (:estabelecimento_id, :descricao, :tipo_custo, :status_pagamento, :valor, :data_vencimento, :data_pagamento, :mes, :ano, :metodo_pagamento_id, :criado_por)
            ");
            return $stmt->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':descricao' => $descricao,
                ':tipo_custo' => $tipoCusto,
                ':status_pagamento' => $statusPagamento,
                ':valor' => $valor,
                ':data_vencimento' => $dataVencimento,
                ':data_pagamento' => $dataPagamento,
                ':mes' => $mes,
                ':ano' => $ano,
                ':metodo_pagamento_id' => $metodoPagamentoId,
                ':criado_por' => $usuarioId
            ]);
        }
    }
}
