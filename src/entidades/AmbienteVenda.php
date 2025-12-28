<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class AmbienteVenda implements IEntidade
{
    private static string $tabela = 'ambiente_venda';

    public static function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        if (empty(trim($dados['nome'])))
        {
            $erros['nome'] = 'O nome é obrigatório.';
        }

        if (isset($dados['taxa']) && !is_numeric($dados['taxa']) || (float)$dados['taxa'] < 0)
        {
            $erros['taxa'] = 'A taxa deve ser um valor numérico não negativo.';
        }

        // Validação de duplicidade para 'nome'
        if (empty($erros) && !empty(trim($dados['nome'])))
        {
            $filtro = 'nome = ?';
            $valores = [$dados['nome']];

            if ($id !== null)
            {
                $filtro .= ' AND id != ?';
                $valores[] = $id;
            }
            
            $stmt = self::query($conn, 'id', '', $filtro, $valores);

            if ($stmt->fetch())
            {
                $erros['duplicidade'] = 'Já existe um ambiente de venda com este nome.';
            }
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
        if (!empty($erros))
        {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " (status_registro, nome, descricao, taxa, criado_por, data_criacao) VALUES (1, ?, ?, ?, 1, NOW())";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            empty($dados['descricao']) ? null : $dados['descricao'],
            (float)($dados['taxa'] ?? 0.00)
        ]);
    }

    public static function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id))
        {
            throw new \Exception("ID é obrigatório para edição.", 400);
        }

        if (!self::buscarPorId($conn, $id))
        {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        $erros = self::validar($conn, $dados, $id);
        if (!empty($erros))
        {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "UPDATE " . self::$tabela . " SET nome = ?, descricao = ?, taxa = ?, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            empty($dados['descricao']) ? null : $dados['descricao'],
            (float)($dados['taxa'] ?? 0.00),
            $id
        ]);
    }

    public static function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id))
        {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }

        if (!self::buscarPorId($conn, $id))
        {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        // Exclusão lógica
        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function listar(\PDO $conn, ?array $filtros = null): array
    {
        $stmt = self::query($conn, 'id, nome, descricao, taxa', '', 'status_registro = 1');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id))
        {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $stmt = self::query($conn, 'id, nome, descricao, taxa', '', 'id = ? AND status_registro = 1', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
