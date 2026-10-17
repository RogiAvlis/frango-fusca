<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\AmbienteVenda;

$action = $_GET['action'] ?? 'listar';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'listar') {
    $dados = AmbienteVenda::listarTodos($estabelecimentoId);
    echo json_encode(['data' => $dados]);
    exit;
}

if ($action === 'salvar') {
    $nome = trim($_POST['nome'] ?? '');

    if (empty($nome)) {
        echo json_encode(['success' => false, 'message' => 'O nome do canal de venda é obrigatório.']);
        exit;
    }

    $res = AmbienteVenda::salvar($_POST, $estabelecimentoId, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Canal salvo com sucesso!' : 'Erro ao salvar.']);
    exit;
}

if ($action === 'buscar') {
    $id = (int)($_GET['id'] ?? 0);
    $item = AmbienteVenda::buscarPorId($id, $estabelecimentoId);
    echo json_encode(['success' => (bool)$item, 'data' => $item]);
    exit;
}

if ($action === 'deletar') {
    $id = (int)($_POST['id'] ?? 0);
    $res = AmbienteVenda::deletar($id, $usuarioId, $estabelecimentoId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Registro excluído com sucesso!' : 'Erro ao excluir.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
