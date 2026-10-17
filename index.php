<?php
require_once __DIR__ . '/config/session_check.php';
$page_title = 'Painel do Dia Atual';
include __DIR__ . '/templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <!-- Cabeçalho do Dia Atual (Sem Siglas) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Painel Operacional do Dia Atual</h2>
            <p class="text-muted small mb-0">Acompanhamento das vendas, faturamento e desempenho da loja em <?php echo date('d/m/Y'); ?></p>
        </div>
        <div>
            <a href="<?php echo BASE_URL; ?>/pages/venda/pdv.php" class="btn btn-success fw-bold shadow-sm px-4 me-2">
                <i class="fas fa-cash-register me-2"></i> Abrir Ponto de Venda
            </a>
            <a href="<?php echo BASE_URL; ?>/pages/precificacao/precificacao.php" class="btn btn-warning fw-bold shadow-sm px-4 text-dark">
                <i class="fas fa-calculator me-2"></i> Simulador de Precificação
            </a>
        </div>
    </div>

    <!-- Indicadores Principais do Dia Atual -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Vendas de Hoje -->
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-warning h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Faturamento de Hoje</span>
                        <h3 class="fw-800 text-dark mb-0 mt-1" id="dash-receita-hoje">R$ 0,00</h3>
                        <span class="badge bg-success mt-2 fs-6" id="dash-pedidos-hoje">0 pedidos</span>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-warning">
                        <i class="fas fa-shopping-basket fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Comparativo com o Mesmo Dia da Semana Passada -->
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-info h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Semana Passada (<span id="dash-data-semana-passada">--/--</span>)</span>
                        <h4 class="fw-bold text-dark mb-0 mt-1" id="dash-receita-semana-passada">R$ 0,00</h4>
                        <div class="mt-2">
                            <span class="badge" id="dash-badge-variacao">0%</span>
                            <span class="text-muted small ms-1" id="dash-pedidos-semana-passada">0 pedidos</span>
                        </div>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-info">
                        <i class="fas fa-history fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Produtos em Alerta de Estoque Baixo -->
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-danger h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Estoque Mínimo em Alerta</span>
                        <h3 class="fw-800 text-dark mb-0 mt-1" id="dash-estoque-critico">0</h3>
                        <a href="<?php echo BASE_URL; ?>/pages/estoque/estoque.php" class="text-danger small fw-bold mt-2 d-inline-block text-decoration-none">
                            Ver sugestões de compra <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-danger">
                        <i class="fas fa-exclamation-triangle fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Reserva Financeira Guardada -->
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm bg-white p-3 border-start border-4 border-primary h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Reserva Financeira</span>
                        <h3 class="fw-800 text-dark mb-0 mt-1" id="dash-reserva-acumulada">R$ 0,00</h3>
                        <a href="<?php echo BASE_URL; ?>/pages/financeiro/reserva_financeira.php" class="text-primary small fw-bold mt-2 d-inline-block text-decoration-none">
                            Ver cofrinho da loja <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="bg-light p-3 rounded-circle text-primary">
                        <i class="fas fa-piggy-bank fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Conectados com Dados Reais do Banco -->
    <div class="row g-4 mb-4">
        <!-- Gráfico 1: Faturamento do Dia Atual por Horário -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-clock me-2 text-warning"></i> Faturamento por Horário no Dia de Hoje</h6>
                </div>
                <div class="card-body">
                    <canvas id="chartVendasHora" height="110"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico 2: Distribuição de Vendas de Hoje por Canal -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-store me-2 text-warning"></i> Vendas de Hoje por Canal</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="chartVendasCanal" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    let chartHoraInstance = null;
    let chartCanalInstance = null;

    function carregarDadosDashboardDia() {
        $.getJSON('./api/dashboard.php', function(res) {
            if (res.success && res.data) {
                const d = res.data;

                // 1. Preencher Cards Principais
                $('#dash-receita-hoje').text('R$ ' + d.hoje.receita_bruta.toFixed(2));
                $('#dash-pedidos-hoje').text(d.hoje.qtd_pedidos + (d.hoje.qtd_pedidos === 1 ? ' pedido' : ' pedidos'));

                $('#dash-data-semana-passada').text(d.mesmo_dia_semana_passada.data_formatada);
                $('#dash-receita-semana-passada').text('R$ ' + d.mesmo_dia_semana_passada.receita_bruta.toFixed(2));
                $('#dash-pedidos-semana-passada').text(d.mesmo_dia_semana_passada.qtd_pedidos + ' pedidos');

                const varPct = d.mesmo_dia_semana_passada.variacao_pct;
                const badgeVar = $('#dash-badge-variacao');
                if (varPct >= 0) {
                    badgeVar.removeClass('bg-danger').addClass('bg-success').html('<i class="fas fa-arrow-up me-1"></i>+' + varPct + '%');
                } else {
                    badgeVar.removeClass('bg-success').addClass('bg-danger').html('<i class="fas fa-arrow-down me-1"></i>' + varPct + '%');
                }

                $('#dash-estoque-critico').text(d.estoque_critico);
                $('#dash-reserva-acumulada').text('R$ ' + d.reserva_acumulada.toFixed(2));

                // 2. Gráfico por Hora (Dados Reais)
                const labelsHoras = [];
                const dataFaturamentoHoras = [];
                
                if (d.horas && d.horas.length > 0) {
                    $.each(d.horas, function(i, h) {
                        labelsHoras.push(h.hora + 'h');
                        dataFaturamentoHoras.push(parseFloat(h.faturamento));
                    });
                } else {
                    labelsHoras.push('Sem vendas hoje');
                    dataFaturamentoHoras.push(0);
                }

                const ctxHora = document.getElementById('chartVendasHora').getContext('2d');
                if (chartHoraInstance) chartHoraInstance.destroy();
                
                chartHoraInstance = new Chart(ctxHora, {
                    type: 'bar',
                    data: {
                        labels: labelsHoras,
                        datasets: [{
                            label: 'Faturamento (R$)',
                            data: dataFaturamentoHoras,
                            backgroundColor: '#ff4500',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });

                // 3. Gráfico por Canal (Dados Reais)
                const labelsCanais = [];
                const dataCanais = [];
                const coresCanais = ['#212529', '#ff4500', '#ffb400', '#0d6efd', '#198754'];

                if (d.canais && d.canais.length > 0) {
                    $.each(d.canais, function(i, c) {
                        labelsCanais.push(c.canal_nome);
                        dataCanais.push(parseFloat(c.total_faturamento));
                    });
                } else {
                    labelsCanais.push('Nenhum canal');
                    dataCanais.push(0);
                }

                const ctxCanal = document.getElementById('chartVendasCanal').getContext('2d');
                if (chartCanalInstance) chartCanalInstance.destroy();

                chartCanalInstance = new Chart(ctxCanal, {
                    type: 'doughnut',
                    data: {
                        labels: labelsCanais,
                        datasets: [{
                            data: dataCanais,
                            backgroundColor: coresCanais
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        });
    }

    carregarDadosDashboardDia();
});
</script>

<?php include __DIR__ . '/templates/footer.php'; ?>