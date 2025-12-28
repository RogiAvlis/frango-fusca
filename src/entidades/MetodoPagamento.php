<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class MetodoPagamento implements IEntidade
{
    private static string $tabela = 'metodo_pagamento';

    public static function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        if (empty(trim($dados['nome']))) {
            $erros['nome'] = 'O nome é obrigatório.';
        }

        // Validação de duplicidade
        if (empty($erros)) {
            $filtro = 'nome = ? AND banco <=> ? AND agencia <=> ? AND conta <=> ?';
            $valores = [
                $dados['nome'],
                empty($dados['banco']) ? null : $dados['banco'],
                empty($dados['agencia']) ? null : $dados['agencia'],
                empty($dados['conta']) ? null : $dados['conta']
            ];

            if ($id !== null) {
                $filtro .= ' AND id != ?';
                $valores[] = $id;
            }
            
            // Usamos o operador <=> para comparar com NULL de forma segura
            $stmt = self::query($conn, 'id', '', $filtro, $valores);

            if ($stmt->fetch()) {
                $erros['duplicidade'] = 'Já existe um método de pagamento com estes dados.';
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
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " (nome, banco, agencia, conta, criado_por, data_criacao) VALUES (?, ?, ?, ?, 1, NOW())";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$dados['nome'], $dados['banco'], $dados['agencia'], $dados['conta']]);
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

        $sql = "UPDATE " . self::$tabela . " SET nome = ?, banco = ?, agencia = ?, conta = ?, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$dados['nome'], $dados['banco'], $dados['agencia'], $dados['conta'], $id]);
    }

    public static function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }

        if (!self::buscarPorId($conn, $id)) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        // Exclusão física, conforme especificado
        $sql = "DELETE FROM " . self::$tabela . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function listar(\PDO $conn, ?array $filtros = null): array
    {
        $stmt = self::query($conn, 'id, nome, banco, agencia, conta');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $stmt = self::query($conn, 'id, nome, banco, agencia, conta', '', 'id = ?', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
