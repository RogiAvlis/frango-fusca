<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\Receita;
use FrangoFusca\Models\Produto;

$action = $_GET['action'] ?? 'buscar';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$usuarioId = $_SESSION['usuario_id'];

if ($action === 'buscar') {
    $produtoId = (int)($_GET['produto_id'] ?? 0);
    $ingredientes = Receita::buscarIngredientesDoProduto($produtoId);
    echo json_encode(['success' => true, 'data' => $ingredientes]);
    exit;
}

if ($action === 'salvar') {
    $produtoPrincipalId = (int)($_POST['produto_principal_id'] ?? 0);
    $ingredientes = $_POST['ingredientes'] ?? [];

    if (!$produtoPrincipalId) {
        echo json_encode(['success' => false, 'message' => 'Selecione o produto principal da receita.']);
        exit;
    }

    $res = Receita::salvarReceitaOficial($produtoPrincipalId, $ingredientes, $usuarioId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Ficha Técnica oficial salva e custo atualizado com sucesso!' : 'Erro ao salvar ficha técnica.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
