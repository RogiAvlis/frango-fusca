<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Database\Connection;

$action = $_GET['action'] ?? 'vendas';
$estabelecimentoId = $_SESSION['estabelecimento_id'];
$db = Connection::getInstance();

if ($action === 'vendas') {
    $dataInicio = $_GET['data_inicio'] ?? date('Y-m-01');
    $dataFim = $_GET['data_fim'] ?? date('Y-m-t');

    $stmt = $db->prepare("
        SELECT v.*, c.nome as cliente_nome, a.nome as canal_nome, m.nome as metodo_nome
        FROM venda v
        LEFT JOIN cliente c ON c.id = v.cliente_id
        INNER JOIN ambiente_venda a ON a.id = v.ambiente_venda_id
        INNER JOIN metodo_pagamento m ON m.id = v.metodo_pagamento_id
        WHERE v.estabelecimento_id = :id AND v.status_registro = 1
          AND DATE(v.data_venda) BETWEEN :inicio AND :fim
        ORDER BY v.data_venda DESC
    ");
    $stmt->execute([':id' => $estabelecimentoId, ':inicio' => $dataInicio, ':fim' => $dataFim]);
    $vendas = $stmt->fetchAll();

    echo json_encode(['data' => $vendas]);
    exit;
}

if ($action === 'dre_financeiro') {
    $mes = (int)($_GET['mes'] ?? date('n'));
    $ano = (int)($_GET['ano'] ?? date('Y'));

    // 1. Receita Bruta de Vendas do Mês
    $stmtVendas = $db->prepare("
        SELECT SUM(valor_total) as receita_bruta, SUM(taxa_canal_aplicada) as taxas_canais, SUM(taxa_maquina_aplicada) as taxas_maquinas
        FROM venda
        WHERE estabelecimento_id = :id AND status_registro = 1
          AND MONTH(data_venda) = :mes AND YEAR(data_venda) = :ano
    ");
    $stmtVendas->execute([':id' => $estabelecimentoId, ':mes' => $mes, ':ano' => $ano]);
    $resVendas = $stmtVendas->fetch();

    $receitaBruta = (float)($resVendas['receita_bruta'] ?? 0);
    $taxasCanais = (float)($resVendas['taxas_canais'] ?? 0);
    $taxasMaquinas = (float)($resVendas['taxas_maquinas'] ?? 0);

    // 2. Custos Mensais (Fixos e Variáveis)
    $stmtCustos = $db->prepare("
        SELECT 
            SUM(CASE WHEN tipo_custo = 'fixo' THEN valor ELSE 0 END) as custos_fixos,
            SUM(CASE WHEN tipo_custo = 'variavel' THEN valor ELSE 0 END) as custos_variaveis
        FROM custo_mensal
        WHERE estabelecimento_id = :id AND status_registro = 1
          AND mes = :mes AND ano = :ano
    ");
    $stmtCustos->execute([':id' => $estabelecimentoId, ':mes' => $mes, ':ano' => $ano]);
    $resCustos = $stmtCustos->fetch();

    $custosFixos = (float)($resCustos['custos_fixos'] ?? 0);
    $custosVariaveis = (float)($resCustos['custos_variaveis'] ?? 0);

    $lucroLiquido = $receitaBruta - $taxasCanais - $taxasMaquinas - $custosFixos - $custosVariaveis;

    echo json_encode([
        'success' => true,
        'data' => [
            'receita_bruta' => $receitaBruta,
            'taxas_canais' => $taxasCanais,
            'taxas_maquinas' => $taxasMaquinas,
            'custos_fixos' => $custosFixos,
            'custos_variaveis' => $custosVariaveis,
            'lucro_liquido' => $lucroLiquido
        ]
    ]);
    exit;
}

if ($action === 'margens') {
    $stmt = $db->prepare("
        SELECT p.nome, p.tipo_produto, p.preco_custo, p.preco_venda, 
               (p.preco_venda - p.preco_custo) as margem_bruta_r,
               CASE WHEN p.preco_custo > 0 THEN ((p.preco_venda - p.preco_custo) / p.preco_custo) * 100 ELSE 0 END as margem_bruta_pct
        FROM produto p
        WHERE p.estabelecimento_id = :id AND p.status_registro = 1 AND p.tipo_produto != 'insumo'
        ORDER BY margem_bruta_r DESC
    ");
    $stmt->execute([':id' => $estabelecimentoId]);
    echo json_encode(['data' => $stmt->fetchAll()]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
