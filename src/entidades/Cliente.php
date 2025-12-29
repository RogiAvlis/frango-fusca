<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Cliente implements IEntidade
{
    private static string $tabela = 'cliente';

    /**
     * Valida os dados para cadastro ou edição de um cliente.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro (para edições, a fim de evitar auto-duplicação).
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        if (empty(trim($dados['nome']))) {
            $erros['nome'] = 'O nome é obrigatório.';
        }

        if (!empty(trim($dados['nome']))) {
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
                $erros['nome'] = 'Já existe um cliente com este nome.';
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
     * Cadastra um novo cliente no banco de dados.
     * O campo `data_criacao` é preenchido automaticamente pelo banco de dados.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados do novo registro.
     * @return bool Retorna true em caso de sucesso.
     * @throws \Exception Se houver erros de validação.
     */
    public function cadastrar(\PDO $conn, array $dados, int $idUsuario): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " (nome, telefone, criado_por) VALUES (:nome, :telefone, :criado_por)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':telefone' => empty($dados['telefone']) ? null : $dados['telefone'],
            ':criado_por' => $idUsuario
        ]);
    }

    /**
     * Edita um cliente existente.
     * O campo `data_alteracao` é atualizado automaticamente pelo banco de dados.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param int|null $id O ID do registro a ser editado.
     * @param array $dados Os novos dados.
     * @return bool Retorna true em caso de sucesso.
     * @throws \Exception Se o ID não for fornecido, o registro não for encontrado ou houver erros de validação.
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

        $sql = "UPDATE " . self::$tabela . " SET nome = :nome, telefone = :telefone, alterado_por = :alterado_por WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':telefone' => empty($dados['telefone']) ? null : $dados['telefone'],
            ':alterado_por' => $idUsuario,
            ':id' => $idRegistro
        ]);
    }

    /**
     * Realiza a exclusão lógica de um cliente, definindo `status_registro` como 0.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param int|null $id O ID do registro a ser deletado.
     * @return bool Retorna true em caso de sucesso.
     * @throws \Exception Se o ID não for fornecido ou o registro não for encontrado.
     */
    public function deletar(\PDO $conn, ?int $idRegistro, int $idUsuario): bool
    {
        if (empty($idRegistro)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }
        if (!$this->buscarPorId($conn, $idRegistro)) {
            throw new \Exception("O registro não foi encontrado.", 404);
        }

        // Realiza a exclusão lógica, mantendo o registro no banco.
        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = :alterado_por WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':id' => $idRegistro,
            ':alterado_por' => $idUsuario
        ]);
    }

    /**
     * Lista todos os clientes ativos, ordenados por nome.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param string|null $filtro Filtros adicionais para a consulta.
     * @param array|null $valor Valores para os placeholders do filtro.
     * @return array Uma lista de clientes.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'id, status_registro, nome, telefone';
        $stmt = $this->query($conn, coluna: $cols, filtro: $filtro, valor: $valor, ordem: 'nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um cliente ativo pelo seu ID.
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
        $cols = 'id, status_registro, nome, telefone';
        $stmt = $this->query($conn, coluna: $cols, filtro: 'id = :id', valor: [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
