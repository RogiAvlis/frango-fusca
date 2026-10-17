<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\Estabelecimento;

$action = $_GET['action'] ?? 'buscar';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'buscar') {
    $item = Estabelecimento::buscarPorId($estabelecimentoId);
    echo json_encode(['success' => (bool)$item, 'data' => $item]);
    exit;
}

if ($action === 'salvar') {
    $nome = trim($_POST['nome'] ?? '');

    if (empty($nome)) {
        echo json_encode(['success' => false, 'message' => 'O nome da loja é obrigatório.']);
        exit;
    }

    $res = Estabelecimento::salvar($_POST, $estabelecimentoId, $usuarioId);
    if ($res) {
        $_SESSION['estabelecimento_nome'] = $nome;
    }
    echo json_encode(['success' => $res, 'message' => $res ? 'Dados da loja atualizados com sucesso!' : 'Erro ao atualizar.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
