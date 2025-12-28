<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Armazena a URL de destino para redirecionar após o login (opcional)
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    
    // Constrói o caminho para o index.php na raiz do projeto
    $login_path = rtrim(str_replace(basename(dirname(__DIR__)), '', dirname(__DIR__)), '/\\') . '/index.php';
    
    header('Location: ' . $login_path);
    exit;
}

