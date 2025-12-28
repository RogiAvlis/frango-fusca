<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Dashboard de Vendas";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard de Vendas</h1>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filtro_periodo" class="form-label">Agrupar por</label>
                    <select class="form-select" id="filtro_periodo">
                        <option value="dia" selected>Dia</option>
                        <option value="mes">Mês</option>
                        <option value="ano">Ano</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro_data_inicio" class="form-label">Data de Início</label>
                    <input type="date" class="form-control" id="filtro_data_inicio">
                </div>
                <div class="col-md-3">
                    <label for="filtro_data_fim" class="form-label">Data de Fim</label>
                    <input type="date" class="form-control" id="filtro_data_fim">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" id="btn_aplicar_filtros">
                        <i class="fas fa-filter me-2"></i>Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Vendas por Período</div>
                <div class="card-body">
                    <canvas id="grafico_vendas_periodo"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Top 5 Produtos Vendidos</div>
                <div class="card-body">
                    <canvas id="grafico_top_produtos"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Vendas por Método de Pagamento</div>
                <div class="card-body">
                    <canvas id="grafico_vendas_metodo"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Vendas por Ambiente de Venda</div>
                <div class="card-body">
                    <canvas id="grafico_vendas_ambiente"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Inclui o rodapé que tem os scripts globais e fecha o body/html
require_once __DIR__ . '/../templates/footer.php';
?>

<!-- Scripts específicos da página -->
<script src="../assets/js/dashboard_vendas/index.js"></script>
