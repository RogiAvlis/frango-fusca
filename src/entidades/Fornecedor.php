<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Fornecedor implements IEntidade
{
    private static string $tabela = 'fornecedor';

    /**
     * Valida os dados para cadastro ou edição de um fornecedor.
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

        if (!empty(trim($dados['cnpj_cpf']))) {
            $filtro = 'cnpj_cpf = :cnpj_cpf';
            $valores = [':cnpj_cpf' => $dados['cnpj_cpf']];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }
            
            // A query já busca em registros com `status_registro = 1`
            $stmt = $this->query($conn, coluna: 'id', filtro: $filtro, valor: $valores);

            if ($stmt->fetch()) {
                $erros['cnpj_cpf'] = 'Já existe um fornecedor com este CNPJ/CPF.';
            }
        }

        if (!empty(trim($dados['email']))) {
            if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                $erros['email'] = 'Formato de e-mail inválido.';
            } else {
                $filtro = 'email = :email';
                $valores = [':email' => $dados['email']];

                if ($id !== null) {
                    $filtro .= ' AND id != :id';
                    $valores[':id'] = $id;
                }
                
                $stmt = $this->query($conn, coluna: 'id', filtro: $filtro, valor: $valores);

                if ($stmt->fetch()) {
                    $erros['email'] = 'Já existe um fornecedor com este e-mail.';
                }
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
     * Cadastra um novo fornecedor.
     * `data_criacao` e `status_registro` são gerenciados pelo banco de dados.
     */
    public function cadastrar(\PDO $conn, array $dados, int $idUsuario): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " 
                    (nome, cnpj_cpf, email, telefone, endereco, criado_por) 
                    VALUES (:nome, :cnpj_cpf, :email, :telefone, :endereco, :criado_por)";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':cnpj_cpf' => empty($dados['cnpj_cpf']) ? null : $dados['cnpj_cpf'],
            ':email' => empty($dados['email']) ? null : $dados['email'],
            ':telefone' => empty($dados['telefone']) ? null : $dados['telefone'],
            ':endereco' => empty($dados['endereco']) ? null : $dados['endereco'],
            ':criado_por' => $idUsuario
        ]);
    }

    /**
     * Edita um fornecedor existente.
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

        $sql = "UPDATE " . self::$tabela . " SET 
                    nome = :nome, cnpj_cpf = :cnpj_cpf, email = :email, 
                    telefone = :telefone, endereco = :endereco, alterado_por = :alterado_por
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':cnpj_cpf' => empty($dados['cnpj_cpf']) ? null : $dados['cnpj_cpf'],
            ':email' => empty($dados['email']) ? null : $dados['email'],
            ':telefone' => empty($dados['telefone']) ? null : $dados['telefone'],
            ':endereco' => empty($dados['endereco']) ? null : $dados['endereco'],
            ':alterado_por' => $idUsuario,
            ':id' => $idRegistro
        ]);
    }

    /**
     * Realiza a exclusão lógica de um fornecedor, definindo `status_registro` como 0.
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
     * Lista todos os fornecedores ativos, ordenados por nome.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'id, nome, cnpj_cpf, email, telefone, endereco';
        $stmt = $this->query($conn, coluna: $cols, filtro: $filtro, valor: $valor, ordem: 'nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um fornecedor ativo pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, nome, cnpj_cpf, email, telefone, endereco';
        $stmt = $this->query($conn, coluna: $cols, filtro: 'id = :id', valor: [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
