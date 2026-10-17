<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\CustoMensal;

$action = $_GET['action'] ?? 'listar';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'listar') {
    $dados = CustoMensal::listarComMetodo($estabelecimentoId);
    echo json_encode(['data' => $dados]);
    exit;
}

if ($action === 'salvar') {
    $descricao = trim($_POST['descricao'] ?? '');

    if (empty($descricao)) {
        echo json_encode(['success' => false, 'message' => 'A descrição da despesa é obrigatória.']);
        exit;
    }

    $res = CustoMensal::salvar($_POST, $estabelecimentoId, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Custo/Despesa salvo com sucesso!' : 'Erro ao salvar despesa.']);
    exit;
}

if ($action === 'buscar') {
    $id = (int)($_GET['id'] ?? 0);
    $item = CustoMensal::buscarPorId($id, $estabelecimentoId);
    echo json_encode(['success' => (bool)$item, 'data' => $item]);
    exit;
}

if ($action === 'deletar') {
    $id = (int)($_POST['id'] ?? 0);
    $res = CustoMensal::deletar($id, $usuarioId, $estabelecimentoId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Registro excluído com sucesso!' : 'Erro ao excluir.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
