<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Receita implements IEntidade
{
    private static string $tabela = 'receita';

    /**
     * Valida os dados para cadastro ou edição de uma receita.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro para edição.
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];
        $produto = new Produto();
        $unidadeMedida = new UnidadeMedida();

        if (empty($dados['produto_principal_id'])) {
            $erros['produto_principal_id'] = 'O Produto Principal é obrigatório.';
        } elseif (!$produto->buscarPorId($conn, (int)$dados['produto_principal_id'])) {
            $erros['produto_principal_id'] = 'Produto Principal não encontrado.';
        }

        if (empty($dados['produto_ingrediente_id'])) {
            $erros['produto_ingrediente_id'] = 'O Produto Ingrediente é obrigatório.';
        } elseif (!$produto->buscarPorId($conn, (int)$dados['produto_ingrediente_id'])) {
            $erros['produto_ingrediente_id'] = 'Produto Ingrediente não encontrado.';
        }

        if (isset($dados['produto_principal_id'], $dados['produto_ingrediente_id']) && $dados['produto_principal_id'] == $dados['produto_ingrediente_id']) {
            $erros['produtos'] = 'O Produto Principal não pode ser o mesmo que o Produto Ingrediente.';
        }

        if (!isset($dados['quantidade_necessaria']) || !is_numeric($dados['quantidade_necessaria']) || (float)$dados['quantidade_necessaria'] <= 0) {
            $erros['quantidade_necessaria'] = 'A quantidade necessária deve ser um valor numérico positivo.';
        }

        if (empty($dados['unidade_medida_id'])) {
            $erros['unidade_medida_id'] = 'A Unidade de Medida é obrigatória.';
        } elseif (!$unidadeMedida->buscarPorId($conn, (int)$dados['unidade_medida_id'])) {
            $erros['unidade_medida_id'] = 'Unidade de Medida não encontrada.';
        }

        if (empty($erros)) {
            $filtro = 'produto_principal_id = :produto_principal_id AND produto_ingrediente_id = :produto_ingrediente_id';
            $valores = [
                ':produto_principal_id' => $dados['produto_principal_id'],
                ':produto_ingrediente_id' => $dados['produto_ingrediente_id']
            ];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }

            if ($this->query($conn, 'id', '', $filtro, $valores)->fetch()) {
                $erros['duplicidade'] = 'Esta combinação de Produto Principal e Ingrediente já existe na receita.';
            }
        }
        
        return $erros;
    }

    /**
     * Constrói e executa uma consulta SQL, filtrando automaticamente por `status_registro = 1`.
     */
    public function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement
    {
        $sql = "SELECT {$coluna} FROM " . self::$tabela . " r"; // Alias 'r' for consistency
        if (!empty($join)) $sql .= " {$join}";

        $sql .= " WHERE r.status_registro = 1";
        if (!empty($filtro)) $sql .= " AND {$filtro}";

        if (!empty($agrupamento)) $sql .= " GROUP BY {$agrupamento}";
        if (!empty($ordem)) $sql .= " ORDER BY {$ordem}";
        if (!empty($limit)) $sql .= " LIMIT {$limit}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    /**
     * Cadastra um novo ingrediente a uma receita.
     * `data_criacao` e `status_registro` são gerenciados pelo banco de dados.
     */
    public function cadastrar(\PDO $conn, array $dados, int $idUsuario): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "INSERT INTO " . self::$tabela . " 
                    (produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id, criado_por) 
                    VALUES (:produto_principal_id, :produto_ingrediente_id, :quantidade_necessaria, :unidade_medida_id, :criado_por)";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':produto_principal_id' => (int)$dados['produto_principal_id'],
            ':produto_ingrediente_id' => (int)$dados['produto_ingrediente_id'],
            ':quantidade_necessaria' => (float)$dados['quantidade_necessaria'],
            ':unidade_medida_id' => (int)$dados['unidade_medida_id'],
            ':criado_por' => $idUsuario
        ]);
    }

    /**
     * Edita um ingrediente de uma receita.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function editar(\PDO $conn, ?int $idRegistro, array $dados, int $idUsuario): bool
    {
        if (empty($idRegistro)) throw new \Exception("ID é obrigatório para edição.", 400);
        if (!$this->buscarPorId($conn, $idRegistro)) throw new \Exception("O registro não foi encontrado.", 404);

        $erros = $this->validar($conn, $dados, $idRegistro);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "UPDATE " . self::$tabela . " SET 
                    produto_principal_id = :produto_principal_id, produto_ingrediente_id = :produto_ingrediente_id, 
                    quantidade_necessaria = :quantidade_necessaria, unidade_medida_id = :unidade_medida_id, 
                    alterado_por = :alterado_por
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':produto_principal_id' => (int)$dados['produto_principal_id'],
            ':produto_ingrediente_id' => (int)$dados['produto_ingrediente_id'],
            ':quantidade_necessaria' => (float)$dados['quantidade_necessaria'],
            ':unidade_medida_id' => (int)$dados['unidade_medida_id'],
            ':alterado_por' => $idUsuario,
            ':id' => $idRegistro
        ]);
    }

    /**
     * Realiza a exclusão lógica de um ingrediente da receita.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function deletar(\PDO $conn, ?int $idRegistro, int $idUsuario): bool
    {
        if (empty($idRegistro)) throw new \Exception("ID é obrigatório para exclusão.", 400);
        if (!$this->buscarPorId($conn, $idRegistro)) throw new \Exception("O registro não foi encontrado.", 404);

        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = :alterado_por WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':id' => $idRegistro,
            ':alterado_por' => $idUsuario
        ]);
    }

    /**
     * Lista todos os ingredientes de receitas ativas.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'r.id, pp.nome as produto_principal_nome, pi.nome as produto_ingrediente_nome, r.quantidade_necessaria, um.sigla as unidade_medida_sigla';
        $join = 'JOIN produto pp ON r.produto_principal_id = pp.id 
                 JOIN produto pi ON r.produto_ingrediente_id = pi.id 
                 JOIN unidade_medida um ON r.unidade_medida_id = um.id';
        $stmt = $this->query($conn, $cols, $join, $filtro, $valor, 'pp.nome ASC, pi.nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um ingrediente de receita ativo pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) return null;

        $cols = 'id, produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id';
        $stmt = $this->query($conn, coluna: $cols, filtro: 'r.id = :id', valor: [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
