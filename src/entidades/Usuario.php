<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Usuario implements IEntidade
{
    private static string $tabela = 'usuario';

    /**
     * Valida os dados para cadastro ou edição de usuário.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param array $dados Dados a serem validados (nome, email, senha, etc.).
     * @param int|null $id ID do registro (usado na edição para ignorar o próprio registro na verificação de duplicidade de email).
     * @param bool $validarSenhaObrigatoria Indica se a senha é obrigatória (ex: true para cadastro, false para edição sem alteração de senha).
     * @return array Retorna um array de erros. Se vazio, a validação passou.
     */
    public static function validar(\PDO $conn, array $dados, ?int $id = null, bool $validarSenhaObrigatoria = true): array
    {
        $erros = [];

        if (empty(trim($dados['nome']))) {
            $erros['nome'] = 'O nome é obrigatório.';
        }

        if (empty(trim($dados['email']))) {
            $erros['email'] = 'O e-mail é obrigatório.';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Formato de e-mail inválido.';
        }

        if ($validarSenhaObrigatoria && empty($dados['senha'])) {
            $erros['senha'] = 'A senha é obrigatória.';
        } elseif (!empty($dados['senha']) && strlen($dados['senha']) < 6) { // Exemplo de regra de senha
            $erros['senha'] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        // Validação de duplicidade para 'email'
        if (empty($erros['email'])) { // Só verifica duplicidade se o email for válido e não vazio
            $filtro = 'email = ?';
            $valores = [$dados['email']];

            if ($id !== null) {
                $filtro .= ' AND id != ?';
                $valores[] = $id;
            }
            
            $stmt = self::query($conn, 'id', '', $filtro, $valores);

            if ($stmt->fetch()) {
                $erros['email'] = 'Já existe um usuário com este e-mail.';
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
        $erros = self::validar($conn, $dados, null, true); // Senha é obrigatória no cadastro
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        // Hash da senha
        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO " . self::$tabela . " (status_registro, nome, email, senha, criado_por, data_criacao) VALUES (1, ?, ?, ?, 1, NOW())";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $dados['senha']
        ]);
    }

    public static function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para edição.", 400);
        }

        $usuarioExistente = self::buscarPorId($conn, $id);
        if (!$usuarioExistente) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        // Valida a senha apenas se ela foi fornecida (ou seja, se o usuário quer alterá-la)
        $validarSenhaObrigatoria = !empty($dados['senha']); 
        $erros = self::validar($conn, $dados, $id, $validarSenhaObrigatoria);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $camposUpdate = ['nome = ?', 'email = ?', 'alterado_por = 1', 'data_alteracao = NOW()'];
        $valores = [$dados['nome'], $dados['email']];

        if (!empty($dados['senha'])) { // Se uma nova senha foi fornecida, faça o hash
            $camposUpdate[] = 'senha = ?';
            $valores[] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        }

        $sql = "UPDATE " . self::$tabela . " SET " . implode(', ', $camposUpdate) . " WHERE id = ?";
        $valores[] = $id; // Adiciona o ID ao final dos valores

        $stmt = $conn->prepare($sql);
        return $stmt->execute($valores);
    }

    public static function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }

        if (!self::buscarPorId($conn, $id)) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public static function listar(\PDO $conn, ?array $filtros = null): array
    {
        $cols = 'id, status_registro, nome, email'; // NÃO SELECIONA A SENHA
        $stmt = self::query($conn, $cols, '', 'status_registro = 1');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, status_registro, nome, email'; // NÃO SELECIONA A SENHA
        $stmt = self::query($conn, $cols, '', 'id = ? AND status_registro = 1', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /**
     * Verifica as credenciais de login de um usuário.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param string $email E-mail do usuário.
     * @param string $senha Senha em texto puro fornecida pelo usuário.
     * @return array|null Retorna os dados do usuário (sem a senha) se as credenciais forem válidas, ou null caso contrário.
     */
    public static function verificarCredenciais(\PDO $conn, string $email, string $senha): ?array
    {
        $stmt = self::query($conn, 'id, nome, email, senha', '', 'email = ? AND status_registro = 1', [$email]);
        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            unset($usuario['senha']); // Remove a senha antes de retornar os dados
            return $usuario;
        }
        return null;
    }
}
