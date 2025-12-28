<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class UnidadeMedida implements IEntidade
{
    private static string $tabela = 'unidade_medida';

    /**
     * Valida os dados para cadastro ou edição de uma unidade de medida.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro para edição.
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        if (empty(trim($dados['sigla']))) $erros['sigla'] = 'A sigla é obrigatória.';
        if (empty(trim($dados['nome']))) $erros['nome'] = 'O nome é obrigatório.';

        if (empty($erros)) {
            $filtro = '(sigla = :sigla OR nome = :nome)';
            $valores = [':sigla' => $dados['sigla'], ':nome' => $dados['nome']];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }
            
            if ($this->query($conn, 'id', '', $filtro, $valores)->fetch()) {
                $erros['duplicidade'] = 'Sigla ou nome já cadastrado em um registro ativo.';
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
     * Cadastra uma nova unidade de medida.
     * `data_criacao` e `status_registro` são gerenciados pelo banco de dados.
     */
    public function cadastrar(\PDO $conn, array $dados): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "INSERT INTO " . self::$tabela . " (sigla, nome, criado_por) VALUES (:sigla, :nome, 1)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':sigla' => $dados['sigla'], ':nome' => $dados['nome']]);
    }

    /**
     * Edita uma unidade de medida existente.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para edição.", 400);
        if (!$this->buscarPorId($conn, $id)) throw new \Exception("O registro não foi encontrado.", 404);

        $erros = $this->validar($conn, $dados, $id);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "UPDATE " . self::$tabela . " SET sigla = :sigla, nome = :nome, alterado_por = 1 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':sigla' => $dados['sigla'], ':nome' => $dados['nome'], ':id' => $id]);
    }

    /**
     * Realiza a exclusão lógica de uma unidade de medida.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para exclusão.", 400);
        if (!$this->buscarPorId($conn, $id)) throw new \Exception("O registro não foi encontrado.", 404);

        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Lista todas as unidades de medida ativas.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $stmt = $this->query($conn, 'id, sigla, nome', '', $filtro, $valor);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma unidade de medida ativa pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) return null;

        $stmt = $this->query($conn, 'id, sigla, nome', '', 'id = :id', [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
