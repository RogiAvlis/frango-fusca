<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class MetodoPagamento implements IEntidade
{
    private static string $tabela = 'metodo_pagamento';

    /**
     * Valida os dados para cadastro ou edição de um método de pagamento.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro para evitar auto-duplicação na edição.
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        if (empty(trim($dados['nome']))) {
            $erros['nome'] = 'O nome é obrigatório.';
        }

        if (empty($erros)) {
            // O operador `<=>` (spaceship) é usado para comparações seguras com NULL.
            $filtro = 'nome = :nome AND banco <=> :banco AND agencia <=> :agencia AND conta <=> :conta';
            $valores = [
                ':nome' => $dados['nome'],
                ':banco' => empty($dados['banco']) ? null : $dados['banco'],
                ':agencia' => empty($dados['agencia']) ? null : $dados['agencia'],
                ':conta' => empty($dados['conta']) ? null : $dados['conta']
            ];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }
            
            $stmt = $this->query($conn, coluna: 'id', filtro: $filtro, valor: $valores);

            if ($stmt->fetch()) {
                $erros['duplicidade'] = 'Já existe um método de pagamento com estes dados.';
            }
        }

        return $erros;
    }

    /**
     * Constrói e executa uma consulta SQL genérica na tabela.
     * Nota: Esta tabela não usa exclusão lógica (status_registro).
     */
    public function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement
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

    /**
     * Cadastra um novo método de pagamento.
     * `data_criacao` é gerenciado automaticamente pelo banco de dados.
     */
    public function cadastrar(\PDO $conn, array $dados): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " (nome, banco, agencia, conta, criado_por) VALUES (:nome, :banco, :agencia, :conta, 1)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':banco' => empty($dados['banco']) ? null : $dados['banco'],
            ':agencia' => empty($dados['agencia']) ? null : $dados['agencia'],
            ':conta' => empty($dados['conta']) ? null : $dados['conta']
        ]);
    }

    /**
     * Edita um método de pagamento existente.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para edição.", 400);
        if (!$this->buscarPorId($conn, $id)) throw new \Exception("O registro não foi encontrado.", 404);

        $erros = $this->validar($conn, $dados, $id);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "UPDATE " . self::$tabela . " SET nome = :nome, banco = :banco, agencia = :agencia, conta = :conta, alterado_por = 1 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':banco' => empty($dados['banco']) ? null : $dados['banco'],
            ':agencia' => empty($dados['agencia']) ? null : $dados['agencia'],
            ':conta' => empty($dados['conta']) ? null : $dados['conta'],
            ':id' => $id
        ]);
    }

    /**
     * Realiza a exclusão física de um método de pagamento.
     * CUIDADO: Esta ação é irreversível.
     */
    public function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para exclusão.", 400);
        if (!$this->buscarPorId($conn, $id)) throw new \Exception("O registro não foi encontrado.", 404);

        $sql = "DELETE FROM " . self::$tabela . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Lista todos os métodos de pagamento.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'id, nome, banco, agencia, conta';
        $stmt = $this->query($conn, coluna: $cols, filtro: $filtro, valor: $valor);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um método de pagamento pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para busca.", 400);

        $cols = 'id, nome, banco, agencia, conta';
        $stmt = $this->query($conn, coluna: $cols, filtro: 'id = :id', valor: [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
