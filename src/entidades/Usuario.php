<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Usuario implements IEntidade
{
    private static string $tabela = 'usuario';

    /**
     * Valida os dados para cadastro ou edição de um usuário.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Dados como nome, email e senha.
     * @param int|null $id O ID do usuário (em caso de edição).
     * @param bool $isCadastro Indica se a validação é para um novo cadastro (senha obrigatória).
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null, bool $isCadastro = false): array
    {
        $erros = [];

        if (empty(trim($dados['nome']))) $erros['nome'] = 'O nome é obrigatório.';

        if (empty(trim($dados['email']))) {
            $erros['email'] = 'O e-mail é obrigatório.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Formato de e-mail inválido.';
        }

        if ($isCadastro && empty($dados['senha'])) {
            $erros['senha'] = 'A senha é obrigatória.';
        }
        if (!empty($dados['senha']) && strlen($dados['senha']) < 6) {
            $erros['senha'] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        if (empty($erros['email'])) {
            $filtro = 'email = :email';
            $valores = [':email' => $dados['email']];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }
            
            if ($this->query($conn, 'id', '', $filtro, $valores)->fetch()) {
                $erros['email'] = 'Já existe um usuário com este e-mail.';
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
        if (!empty($limit)) $sql .= " LIMIT {$limit}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    /**
     * Cadastra um novo usuário.
     * `data_criacao` e `status_registro` são gerenciados pelo banco de dados.
     * A senha é criptografada com hash.
     */
    public function cadastrar(\PDO $conn, array $dados): bool
    {
        $erros = $this->validar($conn, $dados, null, true);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "INSERT INTO " . self::$tabela . " (nome, email, senha, criado_por) VALUES (:nome, :email, :senha, 1)";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':senha' => password_hash($dados['senha'], PASSWORD_DEFAULT)
        ]);
    }

    /**
     * Edita um usuário existente.
     * `data_alteracao` é gerenciado pelo banco de dados.
     * A senha só é alterada se um novo valor for fornecido.
     */
    public function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para edição.", 400);
        if (!$this->buscarPorId($conn, $id)) throw new \Exception("O registro não foi encontrado.", 404);

        $isCadastro = false;
        $erros = $this->validar($conn, $dados, $id, $isCadastro);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $setClauses = ['nome = :nome', 'email = :email', 'alterado_por = 1'];
        $valores = [':nome' => $dados['nome'], ':email' => $dados['email']];

        if (!empty($dados['senha'])) {
            $setClauses[] = 'senha = :senha';
            $valores[':senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        }

        $sql = "UPDATE " . self::$tabela . " SET " . implode(', ', $setClauses) . " WHERE id = :id";
        $valores[':id'] = $id;

        $stmt = $conn->prepare($sql);
        return $stmt->execute($valores);
    }

    /**
     * Realiza a exclusão lógica de um usuário.
     * `data_alteracao` é gerenciado pelo banco de dados.
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
     * Lista todos os usuários ativos (sem a senha).
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'id, nome, email';
        $stmt = $this->query($conn, $cols, '', $filtro, $valor);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um usuário ativo pelo seu ID (sem a senha).
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) return null;
        
        $cols = 'id, nome, email';
        $stmt = $this->query($conn, $cols, '', 'id = :id', [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /**
     * Verifica as credenciais de login de um usuário.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param string $email E-mail do usuário.
     * @param string $senha Senha em texto puro.
     * @return array|null Dados do usuário (sem a senha) ou null se inválido.
     */
    public static function verificarCredenciais(\PDO $conn, string $email, string $senha): ?array
    {
        // Busca incluindo a senha, mas sem o filtro padrão de status para permitir login de inativos se necessário no futuro
        $sql = "SELECT id, nome, email, senha FROM " . self::$tabela . " WHERE email = :email AND status_registro = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            unset($usuario['senha']); 
            return $usuario;
        }
        return null;
    }
}
