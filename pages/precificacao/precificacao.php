<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Simulador de Precificação Inteligente';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0"><i class="fas fa-calculator text-warning me-2"></i> Simulador de Precificação Inteligente</h2>
            <p class="text-muted small mb-0">Monte a receita do seu produto, inclua os custos fixos da loja, defina sua margem de lucro e simule taxas de canais de venda em tempo real</p>
        </div>
        <button type="button" class="btn btn-success fw-bold shadow-sm px-4" id="btn-salvar-como-receita">
            <i class="fas fa-bookmark me-2"></i> Salvar Simulação como Receita Oficial
        </button>
    </div>

    <div class="row g-4">
        <!-- Coluna Esquerda: Parâmetros e Montagem de Receita Fictícia -->
        <div class="col-lg-7">
            <!-- Card 1: Ingredientes da Receita Simulado -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-warning">
                        <i class="fas fa-cubes me-2"></i> 1. Composição de Ingredientes & Embalagens 
                        <i class="fas fa-question-circle text-white-50 ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Adicione todos os insumos (frango, temperos, óleos) e embalagens utilizados para preparar 1 unidade."></i>
                    </h6>
                    <button type="button" class="btn btn-sm btn-warning text-dark fw-bold" id="btn-add-sim-insumo">
                        <i class="fas fa-plus me-1"></i> Adicionar Insumo
                    </button>
                </div>
                <div class="card-body p-3">
                    <div id="container-sim-insumos">
                        <!-- Linhas geradas via JS -->
                    </div>
                </div>
            </div>

            <!-- Card 2: Custos Fixos & Rateio -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-building me-2 text-warning"></i> 2. Rateio de Despesas Fixas da Loja (Água, Luz, Gás, Aluguel)
                        <i class="fas fa-question-circle text-muted ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Dividimos o total das despesas da loja pela quantidade de produtos vendidos no mês para que cada venda pague sua fatia das contas."></i>
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="sim-custos-fixos" class="form-label fw-600 small">
                                Despesas Mensais Totais da Loja (R$)
                                <i class="fas fa-question-circle text-primary ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Soma automática das suas contas mensais (água, luz, gás, aluguel) cadastradas no sistema."></i>
                            </label>
                            <input type="number" step="0.01" class="form-control" id="sim-custos-fixos" value="0.00">
                            <div class="form-text small">Valor pré-carregado das suas despesas no sistema.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="sim-volume-mensal" class="form-label fw-600 small">
                                Estimativa de Vendas Mensais (Qtd/Mês)
                                <i class="fas fa-question-circle text-primary ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Quantidade aproximada de produtos assados que você espera vender durante o mês."></i>
                            </label>
                            <input type="number" step="1" class="form-control" id="sim-volume-mensal" value="500">
                            <div class="form-text small">Exemplo: Expectativa de 500 frangos assados no mês.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Margem Desejada, Canais & Maquininhas -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fas fa-percentage me-2 text-warning"></i> 3. Margem de Lucro Desejada & Taxas de Venda
                        <i class="fas fa-question-circle text-muted ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Escolha a porcentagem de lucro que deseja obter e simule as comissões do canal de venda e da maquininha."></i>
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="sim-margem-desejada" class="form-label fw-600 small">
                                Margem de Lucro Desejada (%)
                                <i class="fas fa-question-circle text-primary ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Percentual de ganho desejado sobre o custo total de produção. Ex: 50%."></i>
                            </label>
                            <input type="number" step="0.1" class="form-control" id="sim-margem-desejada" value="50">
                        </div>
                        <div class="col-md-4">
                            <label for="sim-canal" class="form-label fw-600 small">
                                Canal de Venda / Plataforma
                                <i class="fas fa-question-circle text-primary ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Selecione onde o produto será vendido (Balcão, WhatsApp, iFood) para calcular a comissão retida."></i>
                            </label>
                            <select class="form-select" id="sim-canal"></select>
                        </div>
                        <div class="col-md-4">
                            <label for="sim-maquina" class="form-label fw-600 small">
                                Maquininha de Cartão
                                <i class="fas fa-question-circle text-primary ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Selecione a maquininha utilizada na cobrança para embutir a taxa do cartão."></i>
                            </label>
                            <select class="form-select" id="sim-maquina"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Painel de Resultados & Preço Sugerido -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg bg-dark text-white sticky-top" style="top: 80px;">
                <div class="card-header bg-dark border-bottom border-secondary py-3 text-center">
                    <h5 class="fw-800 text-warning mb-0"><i class="fas fa-chart-pie me-2"></i> Demonstrativo do Preço Recomendado</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary">
                        <span class="text-white-50">
                            Custo dos Ingredientes & Embalagem
                            <i class="fas fa-circle-question text-info ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Soma do valor de todos os alimentos e embalagens usados para produzir 1 unidade."></i>
                        </span>
                        <strong class="fs-6 text-white" id="res-custo-insumos">R$ 0,00</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary">
                        <span class="text-white-50">
                            Rateio de Custos Fixos (Água, Luz, Gás, Aluguel)
                            <i class="fas fa-circle-question text-info ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Fatia das despesas fixas da loja atribuída a esta 1 unidade vendida."></i>
                        </span>
                        <strong class="fs-6 text-white" id="res-rateio-fixo">R$ 0,00</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary">
                        <span class="text-white-50">
                            Custo Real Total de Produção (1 un)
                            <i class="fas fa-circle-question text-warning ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Custo completo para colocar 1 produto pronto na prateleira (Ingredientes + Rateio da Loja)."></i>
                        </span>
                        <strong class="fs-5 text-warning" id="res-custo-total-unitario">R$ 0,00</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary">
                        <span class="text-white-50">
                            Preço Recomendado sem Taxas (Venda Direta)
                            <i class="fas fa-circle-question text-info ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Preço de venda ideal para atingir sua margem de lucro sem comissões de plataformas."></i>
                        </span>
                        <strong class="fs-5 text-info" id="res-preco-sugerido-base">R$ 0,00</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
                        <span class="text-white-50">
                            Desconto de Taxas (Canal + Maquininha)
                            <i class="fas fa-circle-question text-danger ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Valor retido em reais e porcentagem pelas plataformas de delivery e cartões."></i>
                        </span>
                        <span class="badge bg-danger fs-6" id="res-taxas-totais">0 %</span>
                    </div>

                    <div class="bg-secondary bg-opacity-25 p-3 rounded-3 text-center mb-3 border border-warning">
                        <span class="text-uppercase small text-warning fw-bold d-block">
                            Preço Final Sugerido no Cardápio / Canal
                            <i class="fas fa-circle-question text-warning ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Preço de tabela recomendado para cadastrar no iFood/WhatsApp garantindo que sua margem não seja consumida pelas taxas."></i>
                        </span>
                        <h1 class="fw-800 text-warning mb-0 display-5" id="res-preco-final-canal">R$ 0,00</h1>
                    </div>

                    <div class="text-center">
                        <span class="text-white-50 small">
                            Lucro Líquido Limpo no Seu Bolso por Venda
                            <i class="fas fa-circle-question text-success ms-1 cursor-pointer" data-bs-toggle="tooltip" title="Sobra em dinheiro real no seu caixa após pagar todos os insumos, despesas da loja e comissões."></i>
                        </span>
                        <h4 class="fw-bold text-success mt-1 mb-0" id="res-lucro-liquido">R$ 0,00</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Salvar Simulação como Receita -->
    <div class="modal fade" id="modal-salvar-receita" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning">Salvar Simulação como Receita Oficial</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-salvar-receita">
                    <div class="modal-body p-4">
                        <p class="text-muted small mb-3">Escolha qual produto final receberá esta composição de ingredientes simulada:</p>
                        <div class="mb-3">
                            <label for="select-salvar-produto-id" class="form-label fw-600">Produto Final</label>
                            <select class="form-select" id="select-salvar-produto-id" required></select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">Confirmar e Salvar no Banco</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/precificacao/index.js"></script>
