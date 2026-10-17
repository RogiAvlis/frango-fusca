<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\Usuario;

$action = $_GET['action'] ?? 'listar';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'listar') {
    $dados = Usuario::listarProfissionais($estabelecimentoId);
    echo json_encode(['data' => $dados]);
    exit;
}

if ($action === 'listar_convites') {
    $dados = Usuario::listarConvites($estabelecimentoId);
    echo json_encode(['data' => $dados]);
    exit;
}

if ($action === 'gerar_convite') {
    $res = Usuario::criarConvite($_POST, $estabelecimentoId, $usuarioId);
    echo json_encode($res);
    exit;
}

if ($action === 'deletar') {
    $id = (int)($_POST['id'] ?? 0);
    // Não permitir auto-exclusão
    if ($id === $usuarioId) {
        echo json_encode(['success' => false, 'message' => 'Você não pode excluir o seu próprio usuário.']);
        exit;
    }
    $res = Usuario::deletar($id, $usuarioId, $estabelecimentoId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Usuário removido da equipe com sucesso!' : 'Erro ao excluir.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
