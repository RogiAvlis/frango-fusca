<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\Banco;

$action = $_GET['action'] ?? 'listar';
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'listar') {
    $dados = Banco::listarTodos();
    echo json_encode(['data' => $dados]);
    exit;
}

if ($action === 'salvar') {
    $nome = trim($_POST['nome'] ?? '');

    if (empty($nome)) {
        echo json_encode(['success' => false, 'message' => 'O nome do banco é obrigatório.']);
        exit;
    }

    $res = Banco::salvar($_POST, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Banco salvo com sucesso!' : 'Erro ao salvar banco.']);
    exit;
}

if ($action === 'buscar') {
    $id = (int)($_GET['id'] ?? 0);
    $item = Banco::buscarPorId($id);
    echo json_encode(['success' => (bool)$item, 'data' => $item]);
    exit;
}

if ($action === 'deletar') {
    $id = (int)($_POST['id'] ?? 0);
    $res = Banco::deletar($id, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Registro excluído com sucesso!' : 'Erro ao excluir.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
