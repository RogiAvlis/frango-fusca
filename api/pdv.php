<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\Venda;
use FrangoFusca\Models\Produto;
use FrangoFusca\Models\Cliente;
use FrangoFusca\Models\AmbienteVenda;
use FrangoFusca\Models\MaquinaVenda;
use FrangoFusca\Models\MetodoPagamento;

$action = $_GET['action'] ?? 'carregar_pdv';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$vendedorId = $_SESSION['usuario_id'];

if ($action === 'carregar_pdv') {
    $todosProdutos = Produto::listarComDetalhes($estabelecimentoId);
    $clientes = Cliente::listarTodos($estabelecimentoId);
    $canais = AmbienteVenda::listarTodos($estabelecimentoId);
    $maquinas = MaquinaVenda::listarTodos($estabelecimentoId);
    $metodos = MetodoPagamento::listarTodos($estabelecimentoId);

    // Filtrar insumos e enriquecer combos com a composição dos itens
    $produtos = [];
    foreach ($todosProdutos as $p) {
        if ($p['tipo_produto'] === 'insumo') continue;
        if ($p['tipo_produto'] === 'combo') {
            $itensCombo = Produto::buscarItensCombo((int)$p['id']);
            $p['composicao'] = array_map(fn($i) => $i['produto_nome'], $itensCombo);
        } else {
            $p['composicao'] = [];
        }
        $produtos[] = $p;
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'produtos' => array_values($produtos),
            'clientes' => $clientes,
            'canais' => $canais,
            'maquinas' => $maquinas,
            'metodos' => $metodos
        ]
    ]);
    exit;
}

if ($action === 'registrar_venda') {
    $res = Venda::registrarVenda($_POST, $estabelecimentoId, $vendedorId);
    echo json_encode(['success' => $res, 'message' => $res ? 'Venda realizada com sucesso e estoque atualizado!' : 'Erro ao registrar venda.']);
    exit;
}

if ($action === 'listar') {
    $vendas = Venda::listarVendas($estabelecimentoId);
    echo json_encode(['data' => $vendas]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
