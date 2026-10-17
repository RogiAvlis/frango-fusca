<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Painel de Análises & Decisões';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <!-- Cabeçalho Estratégico -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0"><i class="fas fa-chart-line text-primary me-2"></i> Painel de Análises & Tomada de Decisão</h2>
            <p class="text-muted small mb-0">Visão consolidada de inteligência comercial, margens de contribuição, custo de canais e recomendações estratégicas</p>
        </div>
        <div class="d-flex align-items-center">
            <label for="filtro-periodo-dias" class="form-label mb-0 me-2 fw-bold text-muted small">Período:</label>
            <select class="form-select form-select-sm fw-bold border-secondary shadow-sm" id="filtro-periodo-dias" style="width: 180px;">
                <option value="7">Últimos 7 dias</option>
                <option value="30" selected>Últimos 30 dias</option>
                <option value="90">Últimos 90 dias</option>
            </select>
        </div>
    </div>

    <!-- Seção de Insights & Recomendações Estratégicas Automatizadas -->
    <div class="card border-0 shadow-sm mb-4 bg-white border-start border-4 border-primary">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-lightbulb text-warning me-2 fs-5"></i> Diagnósticos & Sugestões para Tomada de Decisão</h6>
        </div>
        <div class="card-body p-3" id="container-insights-estrategicos">
            <!-- Inseridos via JS -->
        </div>
    </div>

    <!-- Cards de Indicadores Estratégicos -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-success h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Faturamento Bruto</span>
                        <h3 class="fw-800 text-dark mb-0 mt-1" id="an-faturamento-bruto">R$ 0,00</h3>
                        <span class="text-muted small mt-2 d-inline-block" id="an-total-pedidos">0 vendas no período</span>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-success">
                        <i class="fas fa-dollar-sign fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Faturamento Líquido</span>
                        <h3 class="fw-800 text-primary mb-0 mt-1" id="an-faturamento-liquido">R$ 0,00</h3>
                        <span class="text-muted small mt-2 d-inline-block">Líquido de Taxas</span>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-primary">
                        <i class="fas fa-wallet fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Desconto de Taxas</span>
                        <h3 class="fw-800 text-danger mb-0 mt-1" id="an-total-taxas">R$ 0,00</h3>
                        <span class="badge bg-danger mt-2" id="an-impacto-taxas-pct">0% do Faturamento</span>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-danger">
                        <i class="fas fa-percentage fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Ticket Médio por Venda</span>
                        <h3 class="fw-800 text-dark mb-0 mt-1" id="an-ticket-medio">R$ 0,00</h3>
                        <span class="text-muted small mt-2 d-inline-block">Média por pedido</span>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-warning">
                        <i class="fas fa-receipt fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Analíticos -->
    <div class="row g-4 mb-4">
        <!-- Gráfico 1: Evolução Histórica de Faturamento Bruto vs Líquido -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-area me-2 text-primary"></i> Evolução de Faturamento no Período</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartEvolucaoDiaria" height="120"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico 2: Comparativo de Rentabilidade por Canal -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-balance-scale me-2 text-primary"></i> Rentabilidade Bruta x Líquida por Canal</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartCanaisRentabilidade" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela Curva ABC de Desempenho de Produtos -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-dark text-white py-3">
            <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-list-ol me-2"></i> Curva de Desempenho de Produtos (Mais Vendidos & Lucrativos)</h6>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-curva-abc" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr class="table-light">
                            <th>Produto</th>
                            <th>Classificação</th>
                            <th>Qtd Vendida</th>
                            <th>Preço Custo Unit.</th>
                            <th>Preço Venda Unit.</th>
                            <th>Faturamento Total</th>
                            <th>Estimativa Lucro Bruto</th>
                        </tr>
                    </thead>
                    <tbody id="tabela-curva-abc-body"></tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    let chartEvolucaoInstance = null;
    let chartCanaisInstance = null;

    function carregarAnalisesEstrategicas() {
        const dias = $('#filtro-periodo-dias').val();
        $.getJSON(`${window.APP_BASE||''}/api/dashboard_analitico.php?dias=${dias}`, function(res) {
            if (res.success && res.data) {
                const d = res.data;

                // 1. Preencher Métricas Gerais
                $('#an-faturamento-bruto').text('R$ ' + d.metricas.faturamento_bruto.toFixed(2));
                $('#an-total-pedidos').text(d.metricas.total_vendas + (d.metricas.total_vendas === 1 ? ' venda no período' : ' vendas no período'));
                $('#an-faturamento-liquido').text('R$ ' + d.metricas.faturamento_liquido.toFixed(2));
                $('#an-total-taxas').text('- R$ ' + d.metricas.total_taxas.toFixed(2));
                $('#an-impacto-taxas-pct').text(d.metricas.impacto_taxas_pct + '% do Faturamento');
                $('#an-ticket-medio').text('R$ ' + d.metricas.ticket_medio.toFixed(2));

                // 2. Renderizar Insights Estratégicos
                const containerInsights = $('#container-insights-estrategicos');
                containerInsights.empty();
                if (d.insights && d.insights.length > 0) {
                    $.each(d.insights, function(i, ins) {
                        const alertClass = ins.tipo === 'warning' ? 'alert-warning' : (ins.tipo === 'success' ? 'alert-success' : 'alert-info');
                        const icon = ins.tipo === 'warning' ? 'fa-exclamation-circle' : (ins.tipo === 'success' ? 'fa-check-circle' : 'fa-info-circle');
                        
                        containerInsights.append(`
                            <div class="alert ${alertClass} d-flex align-items-center mb-2 p-3 rounded-3" role="alert">
                                <i class="fas ${icon} fs-4 me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">${ins.titulo}</h6>
                                    <p class="small mb-0">${ins.mensagem}</p>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    containerInsights.append('<div class="text-muted small">Sem alertas estratégicos no período selecionado.</div>');
                }

                // 3. Gráfico de Evolução Diária
                const labelsEvolucao = [];
                const dataBruto = [];
                const dataLiquido = [];
                if (d.evolucao_diaria && d.evolucao_diaria.length > 0) {
                    $.each(d.evolucao_diaria, function(i, item) {
                        const partes = item.data_dia.split('-');
                        labelsEvolucao.push(partes[2] + '/' + partes[1]);
                        dataBruto.push(parseFloat(item.faturamento_bruto));
                        dataLiquido.push(parseFloat(item.faturamento_liquido));
                    });
                }

                const ctxEvolucao = document.getElementById('chartEvolucaoDiaria').getContext('2d');
                if (chartEvolucaoInstance) chartEvolucaoInstance.destroy();
                chartEvolucaoInstance = new Chart(ctxEvolucao, {
                    type: 'line',
                    data: {
                        labels: labelsEvolucao,
                        datasets: [
                            {
                                label: 'Faturamento Bruto (R$)',
                                data: dataBruto,
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.05)',
                                fill: true,
                                tension: 0.3
                            },
                            {
                                label: 'Faturamento Líquido (R$)',
                                data: dataLiquido,
                                borderColor: '#198754',
                                backgroundColor: 'rgba(25, 135, 84, 0.05)',
                                fill: true,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // 4. Gráfico de Canais
                const labelsCanais = [];
                const dataBrutoCanais = [];
                const dataLiquidoCanais = [];

                if (d.canais && d.canais.length > 0) {
                    $.each(d.canais, function(i, c) {
                        labelsCanais.push(c.canal_nome);
                        dataBrutoCanais.push(parseFloat(c.receita_bruta));
                        dataLiquidoCanais.push(parseFloat(c.receita_liquida));
                    });
                }

                const ctxCanais = document.getElementById('chartCanaisRentabilidade').getContext('2d');
                if (chartCanaisInstance) chartCanaisInstance.destroy();
                chartCanaisInstance = new Chart(ctxCanais, {
                    type: 'bar',
                    data: {
                        labels: labelsCanais,
                        datasets: [
                            {
                                label: 'Receita Bruta (R$)',
                                data: dataBrutoCanais,
                                backgroundColor: '#ffb400'
                            },
                            {
                                label: 'Receita Líquida (R$)',
                                data: dataLiquidoCanais,
                                backgroundColor: '#198754'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // 5. Tabela Curva ABC
                const tbodyABC = $('#tabela-curva-abc-body');
                tbodyABC.empty();
                if (d.curva_abc && d.curva_abc.length > 0) {
                    $.each(d.curva_abc, function(i, item) {
                        const qtd = parseFloat(item.qtd_total_vendida || 0);
                        const fat = parseFloat(item.faturamento_total || 0);
                        const cust = parseFloat(item.preco_custo || 0);
                        const vend = parseFloat(item.preco_venda || 0);
                        const lucro = parseFloat(item.estimativa_lucro_bruto || 0);

                        tbodyABC.append(`
                            <tr>
                                <td><strong>${item.produto_nome}</strong></td>
                                <td><span class="badge ${item.tipo_produto === 'combo' ? 'bg-warning text-dark' : 'bg-secondary'}">${item.tipo_produto}</span></td>
                                <td>${qtd.toFixed(3)}</td>
                                <td>R$ ${cust.toFixed(2)}</td>
                                <td>R$ ${vend.toFixed(2)}</td>
                                <td><strong>R$ ${fat.toFixed(2)}</strong></td>
                                <td><span class="text-success fw-bold">R$ ${lucro.toFixed(2)}</span></td>
                            </tr>
                        `);
                    });
                } else {
                    tbodyABC.append('<tr><td colspan="7" class="text-center text-muted">Nenhum dado no período selecionado.</td></tr>');
                }
            }
        });
    }

    $('#filtro-periodo-dias').on('change', carregarAnalisesEstrategicas);
    carregarAnalisesEstrategicas();
});
</script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
