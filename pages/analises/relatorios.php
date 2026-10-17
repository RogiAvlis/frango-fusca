<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Central de Relatórios';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0"><i class="fas fa-file-alt text-warning me-2"></i> Central de Relatórios Inteligentes</h2>
            <p class="text-muted small mb-0">Consulte relatórios operacionais, valoração de estoque, DRE financeiro e margens de lucratividade por abas</p>
        </div>
    </div>

    <!-- Navegação por Abas -->
    <ul class="nav nav-tabs nav-fill mb-4 font-weight-bold" id="tabRelatorios" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active fw-bold py-3" id="vendas-tab" data-bs-toggle="tab" data-bs-target="#tab-vendas" type="button" role="tab">
                <i class="fas fa-shopping-cart me-2"></i> 1. Relatório de Vendas
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-3" id="dre-tab" data-bs-toggle="tab" data-bs-target="#tab-dre" type="button" role="tab">
                <i class="fas fa-chart-bar me-2"></i> 2. DRE Financeiro Simplificado
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-bold py-3" id="margens-tab" data-bs-toggle="tab" data-bs-target="#tab-margens" type="button" role="tab">
                <i class="fas fa-percent me-2"></i> 3. Lucratividade & Margens
            </button>
        </li>
    </ul>

    <div class="tab-content" id="tabRelatoriosContent">
        <!-- Aba 1: Relatório de Vendas -->
        <div class="tab-pane fade show active" id="tab-vendas" role="tabpanel">
            <div class="card border-0 shadow-sm p-3 mb-4 bg-white">
                <form id="form-filtro-vendas" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="rel-data-inicio" class="form-label fw-600">Data Inicial</label>
                        <input type="date" class="form-control" id="rel-data-inicio" value="<?php echo date('Y-m-01'); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="rel-data-fim" class="form-label fw-600">Data Final</label>
                        <input type="date" class="form-control" id="rel-data-fim" value="<?php echo date('Y-m-t'); ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning fw-bold w-100"><i class="fas fa-filter me-1"></i> Filtrar Vendas</button>
                    </div>
                </form>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table id="tabela-relatorio-vendas" class="table table-hover align-middle mb-0 w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Data / Hora</th>
                                    <th>Cliente</th>
                                    <th>Canal de Venda</th>
                                    <th>Método Pagamento</th>
                                    <th>Valor Total (R$)</th>
                                    <th>Lucro Líquido (R$)</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba 2: DRE Financeiro Simplificado -->
        <div class="tab-pane fade" id="tab-dre" role="tabpanel">
            <div class="card border-0 shadow-sm p-4 bg-white mb-4">
                <div class="row g-3 align-items-center mb-4">
                    <div class="col-md-4">
                        <label for="dre-mes" class="form-label fw-600">Mês</label>
                        <select class="form-select" id="dre-mes">
                            <option value="1" <?php echo date('n') == 1 ? 'selected' : ''; ?>>Janeiro</option>
                            <option value="2" <?php echo date('n') == 2 ? 'selected' : ''; ?>>Fevereiro</option>
                            <option value="3" <?php echo date('n') == 3 ? 'selected' : ''; ?>>Março</option>
                            <option value="4" <?php echo date('n') == 4 ? 'selected' : ''; ?>>Abril</option>
                            <option value="5" <?php echo date('n') == 5 ? 'selected' : ''; ?>>Maio</option>
                            <option value="6" <?php echo date('n') == 6 ? 'selected' : ''; ?>>Junho</option>
                            <option value="7" <?php echo date('n') == 7 ? 'selected' : ''; ?>>Julho</option>
                            <option value="8" <?php echo date('n') == 8 ? 'selected' : ''; ?>>Agosto</option>
                            <option value="9" <?php echo date('n') == 9 ? 'selected' : ''; ?>>Setembro</option>
                            <option value="10" <?php echo date('n') == 10 ? 'selected' : ''; ?>>Outubro</option>
                            <option value="11" <?php echo date('n') == 11 ? 'selected' : ''; ?>>Novembro</option>
                            <option value="12" <?php echo date('n') == 12 ? 'selected' : ''; ?>>Dezembro</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="dre-ano" class="form-label fw-600">Ano</label>
                        <input type="number" class="form-control" id="dre-ano" value="<?php echo date('Y'); ?>">
                    </div>
                    <div class="col-md-4 mt-4">
                        <button type="button" class="btn btn-warning fw-bold w-100" id="btn-atualizar-dre"><i class="fas fa-sync me-1"></i> Atualizar DRE</button>
                    </div>
                </div>

                <div class="table-responsive bg-light p-3 rounded border">
                    <table class="table align-middle mb-0 fs-5">
                        <tbody>
                            <tr class="table-success">
                                <td><strong>(+) Receita Bruta de Vendas</strong></td>
                                <td class="text-end"><strong id="dre-val-receita">+ R$ 0,00</strong></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted">(-) Comissões de Canais / Plataformas (iFood, etc.)</td>
                                <td class="text-end text-danger" id="dre-val-canais">- R$ 0,00</td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted">(-) Taxas de Maquininhas de Cartão</td>
                                <td class="text-end text-danger" id="dre-val-maquinas">- R$ 0,00</td>
                            </tr>
                            <tr class="table-warning">
                                <td><strong>(-) Despesas Fixas (Rateio de Água, Luz, Aluguel, Gás)</strong></td>
                                <td class="text-end text-danger"><strong id="dre-val-custos-fixos">- R$ 0,00</strong></td>
                            </tr>
                            <tr>
                                <td class="ps-4 text-muted">(-) Despesas Variáveis</td>
                                <td class="text-end text-danger" id="dre-val-custos-var">- R$ 0,00</td>
                            </tr>
                            <tr class="table-dark text-warning fs-4 fw-800">
                                <td><strong>(=) LUCRO LÍQUIDO DO PERÍODO</strong></td>
                                <td class="text-end"><strong id="dre-val-lucro-liquido">R$ 0,00</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Aba 3: Lucratividade & Margens por Produto -->
        <div class="tab-pane fade" id="tab-margens" role="tabpanel">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table id="tabela-relatorio-margens" class="table table-hover align-middle mb-0 w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Produto Acabado / Combo</th>
                                    <th>Preço de Custo (R$)</th>
                                    <th>Preço de Venda (R$)</th>
                                    <th>Margem Bruta (R$)</th>
                                    <th>Margem %</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    // 1. Relatório de Vendas
    const dtVendas = $('#tabela-relatorio-vendas').DataTable({
        responsive: true,
        language: { url: '../../assets/js/traducaoDataTable.json' },
        columns: [
            { data: 'data_venda', render: d => new Date(d).toLocaleString('pt-BR') },
            { data: 'cliente_nome', render: d => d || 'Balcão' },
            { data: 'canal_nome' },
            { data: 'metodo_nome' },
            { data: 'valor_total', render: d => 'R$ ' + parseFloat(d).toFixed(2) },
            { data: 'valor_liquido', render: d => '<strong class="text-success">R$ ' + parseFloat(d).toFixed(2) + '</strong>' }
        ]
    });

    function carregarVendasRelatorio() {
        const ini = $('#rel-data-inicio').val();
        const fim = $('#rel-data-fim').val();
        $.getJSON(`${window.APP_BASE||''}/api/relatorios.php?action=vendas&data_inicio=${ini}&data_fim=${fim}`, function(res) {
            if (res && res.data) {
                dtVendas.clear().rows.add(res.data).draw();
            }
        });
    }

    $('#form-filtro-vendas').on('submit', function(e) {
        e.preventDefault();
        carregarVendasRelatorio();
    });

    // 2. DRE
    function carregarDRE() {
        const mes = $('#dre-mes').val();
        const ano = $('#dre-ano').val();
        $.getJSON(`${window.APP_BASE||''}/api/relatorios.php?action=dre_financeiro&mes=${mes}&ano=${ano}`, function(res) {
            if (res.success && res.data) {
                const d = res.data;
                $('#dre-val-receita').text('+ R$ ' + d.receita_bruta.toFixed(2));
                $('#dre-val-canais').text('- R$ ' + d.taxas_canais.toFixed(2));
                $('#dre-val-maquinas').text('- R$ ' + d.taxas_maquinas.toFixed(2));
                $('#dre-val-custos-fixos').text('- R$ ' + d.custos_fixos.toFixed(2));
                $('#dre-val-custos-var').text('- R$ ' + d.custos_variaveis.toFixed(2));
                
                const elemLucro = $('#dre-val-lucro-liquido');
                elemLucro.text('R$ ' + d.lucro_liquido.toFixed(2));
                if (d.lucro_liquido >= 0) {
                    elemLucro.removeClass('text-danger').addClass('text-warning');
                } else {
                    elemLucro.removeClass('text-warning').addClass('text-danger');
                }
            }
        });
    }

    $('#btn-atualizar-dre').on('click', carregarDRE);

    // 3. Margens
    const dtMargens = $('#tabela-relatorio-margens').DataTable({
        responsive: true,
        language: { url: '../../assets/js/traducaoDataTable.json' },
        columns: [
            { data: 'nome' },
            { data: 'preco_custo', render: d => 'R$ ' + parseFloat(d).toFixed(2) },
            { data: 'preco_venda', render: d => 'R$ ' + parseFloat(d).toFixed(2) },
            { data: 'margem_bruta_r', render: d => '<strong class="text-success">R$ ' + parseFloat(d).toFixed(2) + '</strong>' },
            { data: 'margem_bruta_pct', render: d => '<span class="badge bg-info fs-6">' + parseFloat(d).toFixed(1) + '%</span>' }
        ]
    });

    function carregarMargens() {
        $.getJSON('../../api/relatorios.php?action=margens', function(res) {
            if (res && res.data) {
                dtMargens.clear().rows.add(res.data).draw();
            }
        });
    }

    // Eventos de abas
    carregarVendasRelatorio();
    $('#dre-tab').on('click', carregarDRE);
    $('#margens-tab').on('click', carregarMargens);
});
</script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
