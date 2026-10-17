<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\UnidadeMedida;

$action = $_GET['action'] ?? 'listar';
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'listar') {
    $dados = UnidadeMedida::listarTodos();
    echo json_encode(['data' => $dados]);
    exit;
}

if ($action === 'salvar') {
    $sigla = trim($_POST['sigla'] ?? '');
    $nome = trim($_POST['nome'] ?? '');

    if (empty($sigla) || empty($nome)) {
        echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
        exit;
    }

    $res = UnidadeMedida::salvar($_POST, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Unidade de medida salva com sucesso!' : 'Erro ao salvar unidade.']);
    exit;
}

if ($action === 'buscar') {
    $id = (int)($_GET['id'] ?? 0);
    $item = UnidadeMedida::buscarPorId($id);
    echo json_encode(['success' => (bool)$item, 'data' => $item]);
    exit;
}

if ($action === 'deletar') {
    $id = (int)($_POST['id'] ?? 0);
    $res = UnidadeMedida::deletar($id, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Registro excluído com sucesso!' : 'Erro ao excluir.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
