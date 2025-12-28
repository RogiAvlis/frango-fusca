<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class CustoMensal implements IEntidade
{
    private static string $tabela = 'custo_mensal';

    public static function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];
        $camposObrigatorios = ['tipo_custo', 'descricao', 'valor', 'data_pagamento', 'mes', 'ano'];

        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                $erros[$campo] = "O campo '$campo' é obrigatório.";
            }
        }

        if (isset($dados['tipo_custo']) && !in_array($dados['tipo_custo'], ['fixo', 'variavel'])) {
            $erros['tipo_custo'] = "O tipo de custo deve ser 'fixo' ou 'variavel'.";
        }

        if (isset($dados['valor']) && (!is_numeric($dados['valor']) || (float)$dados['valor'] < 0)) {
            $erros['valor'] = 'O valor deve ser numérico e não negativo.';
        }

        if (isset($dados['mes']) && (!is_numeric($dados['mes']) || (int)$dados['mes'] < 1 || (int)$dados['mes'] > 12)) {
            $erros['mes'] = 'Mês inválido.';
        }
        
        if (isset($dados['ano']) && (!is_numeric($dados['ano']) || (int)$dados['ano'] < 1900 || (int)$dados['ano'] > 2100)) {
            $erros['ano'] = 'Ano inválido.';
        }

        if (isset($dados['data_pagamento'])) {
            $d = \DateTime::createFromFormat('Y-m-d', $dados['data_pagamento']);
            if (!$d || $d->format('Y-m-d') !== $dados['data_pagamento']) {
                $erros['data_pagamento'] = 'Data de pagamento inválida.';
            }
        }
        
        if (isset($dados['quantidade_parcela']) && (!is_numeric($dados['quantidade_parcela']) || (int)$dados['quantidade_parcela'] < 1)) {
            $erros['quantidade_parcela'] = 'A quantidade de parcelas deve ser no mínimo 1.';
        }
        
        if (isset($dados['status_pagamento']) && !in_array($dados['status_pagamento'], [0, 1])) {
            $erros['status_pagamento'] = 'Status de pagamento inválido.';
        }

        return $erros;
    }

    public static function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement
    {
        $sql = "SELECT {$coluna} FROM " . self::$tabela;
        if (!empty($join)) $sql .= " {$join}";
        if (!empty($filtro)) $sql .= " WHERE {$filtro}";
        if (!empty($agrupamento)) $sql .= " GROUP BY {$agrupamento}";
        if (!empty($ordem)) $sql .= " ORDER BY {$ordem}";
        if (!empty($limite)) $sql .= " LIMIT {$limite}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    public static function cadastrar(\PDO $conn, array $dados): bool
    {
        $erros = self::validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " 
                    (status_registro, status_pagamento, tipo_custo, quantidade_parcela, descricao, valor, data_pagamento, mes, ano, criado_por, data_criacao) 
                    VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)($dados['status_pagamento'] ?? 0),
            $dados['tipo_custo'],
            (int)($dados['quantidade_parcela'] ?? 1),
            $dados['descricao'],
            (float)$dados['valor'],
            $dados['data_pagamento'],
            (int)$dados['mes'],
            (int)$dados['ano']
        ]);
    }

    public static function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para edição.", 400);
        }

        if (!self::buscarPorId($conn, $id)) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        $erros = self::validar($conn, $dados, $id);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "UPDATE " . self::$tabela . " SET 
                    status_pagamento = ?, tipo_custo = ?, quantidade_parcela = ?, descricao = ?, 
                    valor = ?, data_pagamento = ?, mes = ?, ano = ?,
                    alterado_por = 1, data_alteracao = NOW() 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)($dados['status_pagamento'] ?? 0),
            $dados['tipo_custo'],
            (int)($dados['quantidade_parcela'] ?? 1),
            $dados['descricao'],
            (float)$dados['valor'],
            $dados['data_pagamento'],
            (int)$dados['mes'],
            (int)$dados['ano'],
            $id
        ]);
    }

    public static function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }

        if (!self::buscarPorId($conn, $id)) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function listar(\PDO $conn, ?array $filtros = null): array
    {
        $cols = 'id, status_pagamento, tipo_custo, quantidade_parcela, descricao, valor, data_pagamento, mes, ano';
        $stmt = self::query($conn, $cols, '', 'status_registro = 1');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, status_pagamento, tipo_custo, quantidade_parcela, descricao, valor, data_pagamento, mes, ano';
        $stmt = self::query($conn, $cols, '', 'id = ? AND status_registro = 1', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
