<?php
session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use FrangoFusca\Entidades\Usuario;
use FrangoFusca\Db\Conexao;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redireciona para a página de login se não for um POST
    header('Location: index.php');
    exit;
}

try {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);

    if (empty($email) || empty($senha)) {
        throw new \Exception("E-mail e senha são obrigatórios.");
    }

    $conn = Conexao::obterConexao();
    $usuario = Usuario::verificarCredenciais($conn, $email, $senha);

    if ($usuario) {
        // Credenciais válidas, iniciar sessão
        $_SESSION['user_id'] = $usuario['id'];
        $_SESSION['user_name'] = $usuario['nome'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['logged_in'] = true;

        // Redireciona para a página principal (dashboard)
        header('Location: pages/dashboard_vendas.php');
        exit;
    } else {
        // Credenciais inválidas
        throw new \Exception("E-mail ou senha inválidos.");
    }

} catch (\Exception $e) {
    // Armazena a mensagem de erro na sessão para exibição no formulário de login
    $_SESSION['login_error'] = $e->getMessage();
    header('Location: index.php');
    exit;
}
