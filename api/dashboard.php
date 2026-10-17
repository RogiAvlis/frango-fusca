<?php
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Database\Connection;

$estabelecimentoId = $_SESSION['estabelecimento_id'];
$db = Connection::getInstance();

$hoje = date('Y-m-d');
$mesmoDiaSemanaPassada = date('Y-m-d', strtotime('-7 days'));

// 1. Vendas de Hoje
$stmtHoje = $db->prepare("
    SELECT COUNT(*) as qtd_pedidos, COALESCE(SUM(valor_total), 0) as receita_bruta, COALESCE(SUM(valor_liquido), 0) as receita_liquida
    FROM venda
    WHERE estabelecimento_id = :id AND status_registro = 1 AND DATE(data_venda) = :data
");
$stmtHoje->execute([':id' => $estabelecimentoId, ':data' => $hoje]);
$dadosHoje = $stmtHoje->fetch();

// 2. Vendas do Mesmo Dia da Semana Passada
$stmtSemanaPassada = $db->prepare("
    SELECT COUNT(*) as qtd_pedidos, COALESCE(SUM(valor_total), 0) as receita_bruta
    FROM venda
    WHERE estabelecimento_id = :id AND status_registro = 1 AND DATE(data_venda) = :data
");
$stmtSemanaPassada->execute([':id' => $estabelecimentoId, ':data' => $mesmoDiaSemanaPassada]);
$dadosSemanaPassada = $stmtSemanaPassada->fetch();

// Cálculo de variação percentual
$receitaHoje = (float)$dadosHoje['receita_bruta'];
$receitaSemanaPassada = (float)$dadosSemanaPassada['receita_bruta'];
$variacaoReceitaPct = 0;
if ($receitaSemanaPassada > 0) {
    $variacaoReceitaPct = (($receitaHoje - $receitaSemanaPassada) / $receitaSemanaPassada) * 100;
} elseif ($receitaHoje > 0) {
    $variacaoReceitaPct = 100;
}

// 3. Vendas de Hoje por Canal (para o gráfico)
$stmtCanais = $db->prepare("
    SELECT a.nome as canal_nome, COUNT(v.id) as total_pedidos, COALESCE(SUM(v.valor_total), 0) as total_faturamento
    FROM ambiente_venda a
    LEFT JOIN venda v ON v.ambiente_venda_id = a.id AND v.status_registro = 1 AND DATE(v.data_venda) = :data
    WHERE a.estabelecimento_id = :id AND a.status_registro = 1
    GROUP BY a.id, a.nome
");
$stmtCanais->execute([':id' => $estabelecimentoId, ':data' => $hoje]);
$dadosCanais = $stmtCanais->fetchAll();

// 4. Vendas de Hoje por Hora (para o gráfico de linha)
$stmtHoras = $db->prepare("
    SELECT HOUR(data_venda) as hora, COUNT(*) as qtd_pedidos, COALESCE(SUM(valor_total), 0) as faturamento
    FROM venda
    WHERE estabelecimento_id = :id AND status_registro = 1 AND DATE(data_venda) = :data
    GROUP BY HOUR(data_venda)
    ORDER BY hora ASC
");
$stmtHoras->execute([':id' => $estabelecimentoId, ':data' => $hoje]);
$dadosHoras = $stmtHoras->fetchAll();

// 5. Contagem de Produtos em Estoque Crítico
$stmtEstoqueCritico = $db->prepare("
    SELECT COUNT(*) as total_critico
    FROM produto
    WHERE estabelecimento_id = :id AND status_registro = 1 AND quantidade_estoque <= estoque_minimo
");
$stmtEstoqueCritico->execute([':id' => $estabelecimentoId]);
$estoqueCritico = (int)($stmtEstoqueCritico->fetch()['total_critico'] ?? 0);

// 6. Saldo Total Acumulado nas Reservas Financeiras (Cofrinho)
$stmtReserva = $db->prepare("
    SELECT COALESCE(SUM(saldo_atual), 0) as total_reserva
    FROM reserva_financeira
    WHERE estabelecimento_id = :id AND status_registro = 1
");
$stmtReserva->execute([':id' => $estabelecimentoId]);
$reservaAcumulada = (float)($stmtReserva->fetch()['total_reserva'] ?? 0);

echo json_encode([
    'success' => true,
    'data' => [
        'hoje' => [
            'data_formatada' => date('d/m/Y'),
            'qtd_pedidos' => (int)$dadosHoje['qtd_pedidos'],
            'receita_bruta' => $receitaHoje,
            'receita_liquida' => (float)$dadosHoje['receita_liquida']
        ],
        'mesmo_dia_semana_passada' => [
            'data_formatada' => date('d/m/Y', strtotime('-7 days')),
            'qtd_pedidos' => (int)$dadosSemanaPassada['qtd_pedidos'],
            'receita_bruta' => $receitaSemanaPassada,
            'variacao_pct' => round($variacaoReceitaPct, 1)
        ],
        'canais' => $dadosCanais,
        'horas' => $dadosHoras,
        'estoque_critico' => $estoqueCritico,
        'reserva_acumulada' => $reservaAcumulada
    ]
]);
