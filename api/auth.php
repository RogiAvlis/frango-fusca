<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Controllers\AuthController;

$action = $_GET['action'] ?? '';

if ($action === 'login') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $res = AuthController::login($email, $senha);
    echo json_encode($res);
    exit;
}

if ($action === 'aceitar_convite') {
    $email = $_POST['email'] ?? '';
    $codigo = $_POST['codigo'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $res = AuthController::aceitarConvite($email, $codigo, $nome, $senha);
    echo json_encode($res);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
