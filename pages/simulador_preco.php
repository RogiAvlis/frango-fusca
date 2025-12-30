<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Simulador de Preços";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-calculator me-2"></i>Simulador de Preço de Venda</h1>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form id="form-simulador">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="simulador_produto_id" class="form-label">Produto</label>
                        <select class="form-select" id="simulador_produto_id" name="produto_id"></select>
                    </div>
                    <div class="col-md-6">
                        <label for="preco_venda_sugerido" class="form-label">Preço de Venda Sugerido</label>
                        <div class="input-group">
                            <span class="input-group-text">R$</span>
                            <input type="number" class="form-control" id="preco_venda_sugerido" name="preco_venda_sugerido" step="0.01" min="0" value="0.00">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="simulador_ambiente_id" class="form-label">Ambiente de Venda</label>
                        <select class="form-select" id="simulador_ambiente_id" name="ambiente_venda_id"></select>
                    </div>
                    <div class="col-md-6">
                        <label for="simulador_metodo_pagamento_id" class="form-label">Método de Pagamento</label>
                        <select class="form-select" id="simulador_metodo_pagamento_id" name="metodo_pagamento_id"></select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h4><i class="fas fa-chart-line me-2"></i>Resultados da Simulação</h4>
        </div>
        <div class="card-body">
            <div class="row" id="resultados-simulacao">
                <div class="col-md-4 mb-3">
                    <p class="mb-0 text-muted">Preço de Custo</p>
                    <h4 id="resultado_preco_custo">R$ 0,00</h4>
                </div>
                <div class="col-md-4 mb-3">
                    <p class="mb-0 text-muted">Valor de Lucro (R$)</p>
                    <h4 id="resultado_lucro_valor">R$ 0,00</h4>
                </div>
                <div class="col-md-4 mb-3">
                    <p class="mb-0 text-muted">Margem de Lucro (%)</p>
                    <h4 id="resultado_lucro_percentual">0,00%</h4>
                </div>
                 <div class="col-12"><hr></div>
                 <div class="col-md-4 mb-3">
                    <p class="mb-0 text-muted">Custo Final (Custo + Taxas)</p>
                    <h4 id="resultado_custo_final">R$ 0,00</h4>
                </div>
                <div class="col-md-4 mb-3">
                    <p class="mb-0 text-muted">Taxa Ambiente de Venda</p>
                    <h4 id="resultado_taxa_ambiente">R$ 0,00</h4>
                </div>
                <div class="col-md-4 mb-3">
                    <p class="mb-0 text-muted">Taxa Método de Pagamento</p>
                    <h4 id="resultado_taxa_metodo">R$ 0,00</h4>
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
<script src="../assets/js/simulador_preco/index.js"></script>
