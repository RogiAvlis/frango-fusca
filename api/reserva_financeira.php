<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\ReservaFinanceira;

$action = $_GET['action'] ?? 'listar';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'listar') {
    $dados = ReservaFinanceira::listarTodos($estabelecimentoId);
    echo json_encode(['data' => $dados]);
    exit;
}

if ($action === 'salvar') {
    $descricao = trim($_POST['descricao'] ?? '');

    if (empty($descricao)) {
        echo json_encode(['success' => false, 'message' => 'A descrição da reserva é obrigatória.']);
        exit;
    }

    $res = ReservaFinanceira::salvar($_POST, $estabelecimentoId, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Reserva salva com sucesso!' : 'Erro ao salvar reserva.']);
    exit;
}

if ($action === 'aporte') {
    $id = (int)($_POST['id'] ?? 0);
    $valor = (float)str_replace(',', '.', $_POST['valor'] ?? 0);

    if (!$id || $valor == 0) {
        echo json_encode(['success' => false, 'message' => 'Informe uma reserva e um valor válido para aporte/resgate.']);
        exit;
    }

    $res = ReservaFinanceira::atualizarSaldo($id, $valor, $estabelecimentoId, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Saldo da reserva atualizado com sucesso!' : 'Erro ao atualizar saldo.']);
    exit;
}

if ($action === 'buscar') {
    $id = (int)($_GET['id'] ?? 0);
    $item = ReservaFinanceira::buscarPorId($id, $estabelecimentoId);
    echo json_encode(['success' => (bool)$item, 'data' => $item]);
    exit;
}

if ($action === 'deletar') {
    $id = (int)($_POST['id'] ?? 0);
    $res = ReservaFinanceira::deletar($id, $usuarioId, $estabelecimentoId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Registro excluído com sucesso!' : 'Erro ao excluir.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
