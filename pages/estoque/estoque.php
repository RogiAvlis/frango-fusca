<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Entrada de Estoque & Compras';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Entrada de Estoque & Reposições</h2>
            <p class="text-muted small mb-0">Registre compras N vezes abastecendo o estoque, vinculando a fornecedores e cartões/contas bancárias</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-cart-plus me-2"></i> Nova Compra / Entrada de Lote
        </button>
    </div>

    <!-- Seção de Sugestão de Compras / Alertas de Estoque Baixo -->
    <div class="card border-0 shadow-sm mb-4 border-start border-4 border-warning overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="fas fa-lightbulb me-2 text-warning fs-5"></i> Sugestão Inteligente de Reposição & Produtos em Falta
            </h6>
            <span id="alerta-sugestao-count" class="badge bg-danger rounded-pill d-none">0</span>
        </div>
        <div class="card-body p-3 bg-light">
            <div class="table-responsive">
                <table id="tabela-sugestao" class="table table-hover align-middle mb-0 w-100 bg-white rounded border">
                    <thead class="table-dark">
                        <tr>
                            <th>Produto em Alerta</th>
                            <th>Estoque Atual</th>
                            <th>Estoque Mínimo</th>
                            <th>Sugestão de Compra</th>
                            <th>Fornecedor Preferencial</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabela Principal de Histórico de Lotes Comprados -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-dark text-white py-3">
            <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-boxes me-2"></i> Histórico de Entradas de Estoque</h6>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-estoque" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Data Entrada</th>
                            <th>Produto / Insumo</th>
                            <th>Qtd Adquirida</th>
                            <th>Preço Custo Unit.</th>
                            <th>Valor Total (R$)</th>
                            <th>Forma de Pagamento</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-data_entrada">
                                <input type="text" list="datalist-filtro-data_entrada" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-data_entrada"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-produto_nome">
                                <input type="text" list="datalist-filtro-produto_nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-produto_nome"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-quantidade">
                                <input type="text" list="datalist-filtro-quantidade" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-quantidade"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-preco_custo_unitario">
                                <input type="text" list="datalist-filtro-preco_custo_unitario" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-preco_custo_unitario"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-valor_total">
                                <input type="text" list="datalist-filtro-valor_total" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-valor_total"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-metodo_pagamento_nome">
                                <input type="text" list="datalist-filtro-metodo_pagamento_nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-metodo_pagamento_nome"></datalist>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Entrada de Lote -->
    <div class="modal fade" id="modal-estoque" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning">Registrar Compra / Entrada de Estoque</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-estoque">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="produto_id" class="form-label fw-600">Selecione o Produto / Insumo</label>
                            <select class="form-select" id="produto_id" name="produto_id" required>
                                <option value="">Selecione...</option>
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="quantidade" class="form-label fw-600">Quantidade Comprada</label>
                                <input type="number" step="0.001" class="form-control" id="quantidade" name="quantidade" required placeholder="Ex: 50.000">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="preco_custo_unitario" class="form-label fw-600">Preço de Custo Unitário (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="preco_custo_unitario" name="preco_custo_unitario" required placeholder="Ex: 8.50">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="fornecedor_id" class="form-label fw-600">Fornecedor (Opcional)</label>
                                <select class="form-select" id="fornecedor_id" name="fornecedor_id">
                                    <option value="">Nenhum / Não Informado</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="metodo_pagamento_id" class="form-label fw-600">Método de Pagamento / Cartão Usado</label>
                                <select class="form-select" id="metodo_pagamento_id" name="metodo_pagamento_id">
                                    <option value="">Nenhum / Não Informado</option>
                                </select>
                                <div class="form-text small">Vincula esta compra de estoque ao cartão ou conta da loja.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label fw-600">Observações / Número da Nota Fiscal</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="2" placeholder="Informações do lote ou NF"></textarea>
                        </div>

                        <input type="hidden" id="unidade_medida_id" name="unidade_medida_id" value="1">
                    </div>
                    <div class="modal-footer border-0 bg-light px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">Confirmar Entrada no Estoque</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/estoque/index.js"></script>
