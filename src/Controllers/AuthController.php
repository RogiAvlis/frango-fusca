<?php
namespace FrangoFusca\Controllers;

use FrangoFusca\Database\Connection;
use PDO;

class AuthController {
    public static function login(string $email, string $senha): array {
        $db = Connection::getInstance();

        $stmt = $db->prepare("
            SELECT u.*, e.nome as estabelecimento_nome 
            FROM usuario u
            LEFT JOIN estabelecimento e ON e.id = u.estabelecimento_id
            WHERE u.email = :email AND u.status_registro = 1
            LIMIT 1
        ");
        $stmt->execute([':email' => trim($email)]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            return ['success' => false, 'message' => 'E-mail ou senha incorretos.'];
        }

        $senhaValida = password_verify($senha, $usuario['senha']);

        // Suporte a legado / inserção manual via MD5 com atualização automática para Bcrypt
        if (!$senhaValida && md5($senha) === $usuario['senha']) {
            $senhaValida = true;
            // Atualiza automaticamente o banco de dados para o padrão seguro Bcrypt
            $novoHash = password_hash($senha, PASSWORD_BCRYPT);
            $updateStmt = $db->prepare("UPDATE usuario SET senha = :senha WHERE id = :id");
            $updateStmt->execute([':senha' => $novoHash, ':id' => $usuario['id']]);
        }

        if (!$senhaValida) {
            return ['success' => false, 'message' => 'E-mail ou senha incorretos.'];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['usuario_id'] = (int)$usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_perfil'] = $usuario['perfil'];
        $_SESSION['estabelecimento_id'] = (int)$usuario['estabelecimento_id'];
        $_SESSION['estabelecimento_nome'] = $usuario['estabelecimento_nome'] ?? 'Frango Fusca';

        return ['success' => true, 'message' => 'Login efetuado com sucesso.'];
    }

    public static function aceitarConvite(string $email, string $codigo, string $nome, string $senha): array {
        $db = Connection::getInstance();

        $stmt = $db->prepare("
            SELECT * FROM convite_usuario 
            WHERE email = :email AND codigo_convite = :codigo AND status_convite = 'pendente'
            LIMIT 1
        ");
        $stmt->execute([
            ':email' => trim($email),
            ':codigo' => trim($codigo)
        ]);
        $convite = $stmt->fetch();

        if (!$convite) {
            return ['success' => false, 'message' => 'Convite inválido, expirado ou e-mail/código não coincidem.'];
        }

        // Verifica se já existe usuário com esse e-mail
        $stmtUser = $db->prepare("SELECT id FROM usuario WHERE email = :email LIMIT 1");
        $stmtUser->execute([':email' => trim($email)]);
        if ($stmtUser->fetch()) {
            return ['success' => false, 'message' => 'Este e-mail já possui um cadastro ativo no sistema.'];
        }

        $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

        $db->beginTransaction();
        try {
            $stmtInsert = $db->prepare("
                INSERT INTO usuario (estabelecimento_id, nome, email, senha, perfil, criado_por) 
                VALUES (:estabelecimento_id, :nome, :email, :senha, :perfil, :criado_por)
            ");
            $stmtInsert->execute([
                ':estabelecimento_id' => $convite['estabelecimento_id'],
                ':nome' => trim($nome),
                ':email' => trim($email),
                ':senha' => $senhaHash,
                ':perfil' => $convite['perfil'],
                ':criado_por' => $convite['criado_por']
            ]);

            $stmtUpdateConvite = $db->prepare("
                UPDATE convite_usuario 
                SET status_convite = 'aceito', data_aceite = NOW() 
                WHERE id = :id
            ");
            $stmtUpdateConvite->execute([':id' => $convite['id']]);

            $db->commit();
            return ['success' => true, 'message' => 'Cadastro concluído com sucesso! Você já pode realizar o login.'];
        } catch (\Exception $e) {
            $db->rollBack();
            return ['success' => false, 'message' => 'Erro ao processar o convite: ' . $e->getMessage()];
        }
    }

    public static function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}
