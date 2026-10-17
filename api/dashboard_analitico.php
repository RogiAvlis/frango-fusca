<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Database\Connection;

$estabelecimentoId = $_SESSION['estabelecimento_id'];
$db = Connection::getInstance();

$periodoDias = (int)($_GET['dias'] ?? 30);
$dataInicio = date('Y-m-d', strtotime("-{$periodoDias} days"));
$dataFim = date('Y-m-d');

// 1. Métricas Gerais Efetivas (Últimos N dias)
$stmtMetricas = $db->prepare("
    SELECT 
        COUNT(*) as total_vendas,
        COALESCE(SUM(valor_total), 0) as faturamento_bruto,
        COALESCE(SUM(taxa_canal_aplicada + taxa_maquina_aplicada), 0) as total_taxas,
        COALESCE(SUM(valor_liquido), 0) as faturamento_liquido,
        COALESCE(AVG(valor_total), 0) as ticket_medio
    FROM venda
    WHERE estabelecimento_id = :id AND status_registro = 1
      AND DATE(data_venda) BETWEEN :inicio AND :fim
");
$stmtMetricas->execute([':id' => $estabelecimentoId, ':inicio' => $dataInicio, ':fim' => $dataFim]);
$metricas = $stmtMetricas->fetch();

$faturamentoBruto = (float)$metricas['faturamento_bruto'];
$totalTaxas = (float)$metricas['total_taxas'];
$impactoTaxasPct = ($faturamentoBruto > 0) ? ($totalTaxas / $faturamentoBruto) * 100 : 0;

// 2. Análise por Canal de Venda (Receita Bruta vs Taxas vs Receita Líquida)
$stmtCanais = $db->prepare("
    SELECT 
        a.nome as canal_nome,
        COUNT(v.id) as qtd_vendas,
        COALESCE(SUM(v.valor_total), 0) as receita_bruta,
        COALESCE(SUM(v.taxa_canal_aplicada + v.taxa_maquina_aplicada), 0) as taxas_pagas,
        COALESCE(SUM(v.valor_liquido), 0) as receita_liquida
    FROM ambiente_venda a
    LEFT JOIN venda v ON v.ambiente_venda_id = a.id AND v.status_registro = 1 AND DATE(v.data_venda) BETWEEN :inicio AND :fim
    WHERE a.estabelecimento_id = :id AND a.status_registro = 1
    GROUP BY a.id, a.nome
    ORDER BY receita_bruta DESC
");
$stmtCanais->execute([':id' => $estabelecimentoId, ':inicio' => $dataInicio, ':fim' => $dataFim]);
$canaisAnalise = $stmtCanais->fetchAll();

// 3. Curva ABC de Produtos (Top Mais Vendidos e Mais Lucrativos)
$stmtCurvaABC = $db->prepare("
    SELECT 
        p.nome as produto_nome,
        p.tipo_produto,
        SUM(iv.quantidade) as qtd_total_vendida,
        SUM(iv.subtotal) as faturamento_total,
        p.preco_custo,
        p.preco_venda,
        (SUM(iv.subtotal) - (SUM(iv.quantidade) * p.preco_custo)) as estimativa_lucro_bruto
    FROM item_venda iv
    INNER JOIN venda v ON v.id = iv.venda_id
    INNER JOIN produto p ON p.id = iv.produto_id
    WHERE v.estabelecimento_id = :id AND v.status_registro = 1 AND DATE(v.data_venda) BETWEEN :inicio AND :fim
    GROUP BY p.id, p.nome, p.tipo_produto, p.preco_custo, p.preco_venda
    ORDER BY faturamento_total DESC
    LIMIT 10
");
$stmtCurvaABC->execute([':id' => $estabelecimentoId, ':inicio' => $dataInicio, ':fim' => $dataFim]);
$curvaABC = $stmtCurvaABC->fetchAll();

// 4. Evolução Diária de Faturamento no Período
$stmtEvolucao = $db->prepare("
    SELECT 
        DATE(data_venda) as data_dia,
        COUNT(*) as qtd_pedidos,
        SUM(valor_total) as faturamento_bruto,
        SUM(valor_liquido) as faturamento_liquido
    FROM venda
    WHERE estabelecimento_id = :id AND status_registro = 1 AND DATE(data_venda) BETWEEN :inicio AND :fim
    GROUP BY DATE(data_venda)
    ORDER BY data_dia ASC
");
$stmtEvolucao->execute([':id' => $estabelecimentoId, ':inicio' => $dataInicio, ':fim' => $dataFim]);
$evolucaoDiaria = $stmtEvolucao->fetchAll();

// 5. Recomendações e Insights Automáticos para Tomada de Decisão
$insights = [];

// Insight de Taxa de Plataforma
if ($impactoTaxasPct > 10) {
    $insights[] = [
        'tipo' => 'warning',
        'titulo' => 'Atenção com Taxas de Plataformas e Maquininhas',
        'mensagem' => sprintf('As taxas comeram %.1f%% do seu faturamento bruto no período (R$ %.2f em taxas). Considere incentivar vendas diretas via WhatsApp ou Balcão.', $impactoTaxasPct, $totalTaxas)
    ];
} else {
    $insights[] = [
        'tipo' => 'success',
        'titulo' => 'Custo de Canais Equilibrado',
        'mensagem' => sprintf('Seu custo de taxas de intermediação está sob controle em %.1f%% do faturamento bruto.', $impactoTaxasPct)
    ];
}

// Insight de Ticket Médio
$ticketMedioVal = (float)$metricas['ticket_medio'];
if ($ticketMedioVal > 0 && $ticketMedioVal < 35) {
    $insights[] = [
        'tipo' => 'info',
        'titulo' => 'Oportunidade para Aumentar o Ticket Médio',
        'mensagem' => sprintf('Seu ticket médio atual é de R$ %.2f. Crie novos Combos (Ex: Frango Assado + Bebida + Acompanhamento) para elevar o valor gasto por pedido.', $ticketMedioVal)
    ];
}

echo json_encode([
    'success' => true,
    'data' => [
        'metricas' => [
            'total_vendas' => (int)$metricas['total_vendas'],
            'faturamento_bruto' => $faturamentoBruto,
            'total_taxas' => $totalTaxas,
            'faturamento_liquido' => (float)$metricas['faturamento_liquido'],
            'ticket_medio' => $ticketMedioVal,
            'impacto_taxas_pct' => round($impactoTaxasPct, 1)
        ],
        'canais' => $canaisAnalise,
        'curva_abc' => $curvaABC,
        'evolucao_diaria' => $evolucaoDiaria,
        'insights' => $insights
    ]
]);
