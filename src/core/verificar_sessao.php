<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';

// Verifica se o usuário está logado. Se não, redireciona para a página de login.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {

    // Armazena a URL de destino para redirecionar após o login (opcional)
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    // Redireciona para a página de login usando a BASE_URL
    header('Location: ' . BASE_URL);
    exit;
}

