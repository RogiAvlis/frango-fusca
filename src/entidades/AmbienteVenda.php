<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class AmbienteVenda implements IEntidade
{
    private static string $tabela = 'ambiente_venda';

    /**
     * Valida os dados para cadastro ou edição de um ambiente de venda.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro (para edições).
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        if (empty(trim($dados['nome']))) {
            $erros['nome'] = 'O nome é obrigatório.';
        }

        if (isset($dados['taxa']) && !is_numeric($dados['taxa']) || (float)$dados['taxa'] < 0) {
            $erros['taxa'] = 'A taxa deve ser um valor numérico não negativo.';
        }

        if (empty($erros) && !empty(trim($dados['nome']))) {
            $filtro = 'nome = :nome';
            $valores = [':nome' => $dados['nome']];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }

            // A função query já filtra por `status_registro = 1`,
            // então a verificação de duplicidade ocorre apenas em registros ativos.
            $stmt = $this->query($conn, coluna: 'id', filtro: $filtro, valor: $valores);

            if ($stmt->fetch()) {
                $erros['duplicidade'] = 'Já existe um ambiente de venda com este nome.';
            }
        }

        return $erros;
    }

    /**
     * Constrói e executa uma consulta SQL, filtrando automaticamente por `status_registro = 1`.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param string $coluna As colunas a serem selecionadas.
     * @param string $join Cláusulas JOIN adicionais.
     * @param string $filtro Condições WHERE adicionais.
     * @param array $valor Os valores para os placeholders da consulta.
     * @param string $ordem A ordenação dos resultados.
     * @param string $agrupamento O agrupamento dos resultados.
     * @param string $limit O limite de resultados.
     * @return \PDOStatement O statement preparado e executado.
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
     * Cadastra um novo ambiente de venda no banco de dados.
     * O campo `data_criacao` é preenchido automaticamente pelo banco de dados.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados do novo registro.
     * @return bool Retorna true em caso de sucesso.
     * @throws \Exception Se houver erros de validação.
     */
    public function cadastrar(\PDO $conn, array $dados): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " (nome, descricao, taxa, criado_por) VALUES (:nome, :descricao, :taxa, 1)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => empty($dados['descricao']) ? null : $dados['descricao'],
            ':taxa' => (float)($dados['taxa'] ?? 0.00)
        ]);
    }

    /**
     * Edita um ambiente de venda existente.
     * O campo `data_alteracao` é atualizado automaticamente pelo banco de dados.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param int|null $id O ID do registro a ser editado.
     * @param array $dados Os novos dados.
     * @return bool Retorna true em caso de sucesso.
     * @throws \Exception Se o ID não for fornecido, o registro não for encontrado ou houver erros de validação.
     */
    public function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para edição.", 400);
        }
        if (!$this->buscarPorId($conn, $id)) {
            throw new \Exception("O registro não foi encontrado.", 404);
        }

        $erros = $this->validar($conn, $dados, $id);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "UPDATE " . self::$tabela . " SET nome = :nome, descricao = :descricao, taxa = :taxa, alterado_por = 1 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => empty($dados['descricao']) ? null : $dados['descricao'],
            ':taxa' => (float)($dados['taxa'] ?? 0.00),
            ':id' => $id
        ]);
    }

    /**
     * Realiza a exclusão lógica de um ambiente de venda, definindo `status_registro` como 0.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param int|null $id O ID do registro a ser deletado.
     * @return bool Retorna true em caso de sucesso.
     * @throws \Exception Se o ID não for fornecido ou o registro não for encontrado.
     */
    public function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }
        if (!$this->buscarPorId($conn, $id)) {
            throw new \Exception("O registro não foi encontrado.", 404);
        }

        // Realiza a exclusão lógica, mantendo o registro no banco.
        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Lista todos os ambientes de venda ativos.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param string|null $filtro Filtros adicionais para a consulta.
     * @param array|null $valor Valores para os placeholders do filtro.
     * @return array Uma lista de ambientes de venda.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $stmt = $this->query($conn, coluna: 'id, nome, descricao, taxa', filtro: $filtro, valor: $valor);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um ambiente de venda ativo pelo seu ID.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param int|null $id O ID do registro.
     * @return array|null Retorna os dados do registro ou null se não for encontrado.
     * @throws \Exception Se o ID não for fornecido.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }

        $stmt = $this->query($conn, coluna: 'id, nome, descricao, taxa', filtro: 'id = :id', valor: [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
