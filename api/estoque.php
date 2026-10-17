<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\Estoque;

$action = $_GET['action'] ?? 'listar';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'listar') {
    $dados = Estoque::listarMovimentacoes($estabelecimentoId);
    echo json_encode(['data' => $dados]);
    exit;
}

if ($action === 'registrar_entrada') {
    $produtoId = (int)($_POST['produto_id'] ?? 0);
    $quantidade = (float)($_POST['quantidade'] ?? 0);
    $precoCustoUnitario = (float)($_POST['preco_custo_unitario'] ?? 0);

    if (!$produtoId || $quantidade <= 0 || $precoCustoUnitario <= 0) {
        echo json_encode(['success' => false, 'message' => 'Selecione o produto, informe uma quantidade e um preço de custo válidos.']);
        exit;
    }

    $res = Estoque::registrarEntrada($_POST, $estabelecimentoId, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Entrada de estoque e lote registrados com sucesso!' : 'Erro ao registrar entrada.']);
    exit;
}

if ($action === 'sugestoes_compra') {
    $dados = Estoque::sugestoesDeCompra($estabelecimentoId);
    echo json_encode(['data' => $dados]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
