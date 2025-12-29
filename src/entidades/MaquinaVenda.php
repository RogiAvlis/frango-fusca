<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class MaquinaVenda implements IEntidade
{
    private static string $tabela = 'maquina_venda';

    /**
     * Valida os dados para cadastro ou edição de uma máquina de venda.
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

        if (isset($dados['taxa']) && (!is_numeric($dados['taxa']) || (float)$dados['taxa'] < 0)) {
            $erros['taxa'] = 'A taxa deve ser um valor numérico não negativo.';
        }

        if (empty($erros) && !empty(trim($dados['nome']))) {
            $filtro = 'nome = :nome';
            $valores = [':nome' => $dados['nome']];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }
            
            // A query já busca em registros com `status_registro = 1`, prevenindo duplicidade em registros ativos.
            $stmt = $this->query($conn, coluna: 'id', filtro: $filtro, valor: $valores);

            if ($stmt->fetch()) {
                $erros['duplicidade'] = 'Já existe uma máquina de venda com este nome.';
            }
        }

        return $erros;
    }

    /**
     * Constrói e executa uma consulta SQL, filtrando automaticamente por `status_registro = 1`.
     */
    public function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement
    {
        $sql = "SELECT {$coluna} FROM " . self::$tabela;
        if (!empty($join)) $sql .= " {$join}";

        // Garante que todos os resultados sejam de registros ativos.
        $sql .= " WHERE status_registro = 1";
        if (!empty($filtro)) $sql .= " AND {$filtro}";

        if (!empty($agrupamento)) $sql .= " GROUP BY {$agrupamento}";
        if (!empty($ordem)) $sql .= " ORDER BY {$ordem}";
        if (!empty($limite)) $sql .= " LIMIT {$limite}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    /**
     * Cadastra uma nova máquina de venda.
     * `data_criacao` e `status_registro` são gerenciados pelo banco de dados.
     */
    public function cadastrar(\PDO $conn, array $dados, int $idUsuario): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " (nome, descricao, taxa, criado_por) VALUES (:nome, :descricao, :taxa, :criado_por)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => empty($dados['descricao']) ? null : $dados['descricao'],
            ':taxa' => (float)($dados['taxa'] ?? 0.00),
            ':criado_por' => $idUsuario
        ]);
    }

    /**
     * Edita uma máquina de venda existente.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function editar(\PDO $conn, ?int $idRegistro, array $dados, int $idUsuario): bool
    {
        if (empty($idRegistro)) {
            throw new \Exception("ID é obrigatório para edição.", 400);
        }
        if (!$this->buscarPorId($conn, $idRegistro)) {
            throw new \Exception("O registro não foi encontrado.", 404);
        }

        $erros = $this->validar($conn, $dados, $idRegistro);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "UPDATE " . self::$tabela . " SET nome = :nome, descricao = :descricao, taxa = :taxa, alterado_por = :alterado_por WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => empty($dados['descricao']) ? null : $dados['descricao'],
            ':taxa' => (float)($dados['taxa'] ?? 0.00),
            ':alterado_por' => $idUsuario,
            ':id' => $idRegistro
        ]);
    }

    /**
     * Realiza a exclusão lógica de uma máquina de venda.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function deletar(\PDO $conn, ?int $idRegistro, int $idUsuario): bool
    {
        if (empty($idRegistro)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }
        if (!$this->buscarPorId($conn, $idRegistro)) {
            throw new \Exception("O registro não foi encontrado.", 404);
        }

        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = :alterado_por WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':id' => $idRegistro,
            ':alterado_por' => $idUsuario
        ]);
    }

    /**
     * Lista todas as máquinas de venda ativas.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $stmt = $this->query($conn, 'id, nome, descricao, taxa', '', $filtro, $valor);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma máquina de venda ativa pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $stmt = $this->query($conn, 'id, nome, descricao, taxa', '', 'id = :id', [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
