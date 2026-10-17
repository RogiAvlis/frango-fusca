<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Custos & Despesas';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Custos & Despesas Mensais</h2>
            <p class="text-muted small mb-0">Controle despesas fixas (aluguel, água, luz, gás) e variáveis, integradas ao simulador de precificação</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Adicionar Despesa
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-custos" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Descrição da Despesa</th>
                            <th>Classificação</th>
                            <th>Valor (R$)</th>
                            <th>Status Pagamento</th>
                            <th>Mês / Ano</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-descricao">
                                <input type="text" list="datalist-filtro-descricao" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-descricao"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-tipo_custo">
                                <input type="text" list="datalist-filtro-tipo_custo" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-tipo_custo"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-valor">
                                <input type="text" list="datalist-filtro-valor" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-valor"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-status_pagamento">
                                <input type="text" list="datalist-filtro-status_pagamento" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-status_pagamento"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-mes">
                                <input type="text" list="datalist-filtro-mes" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-mes"></datalist>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Custo -->
    <div class="modal fade" id="modal-custo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-custo-label">Adicionar Despesa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-custo">
                    <div class="modal-body p-4">
                        <input type="hidden" id="custoId" name="id">

                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-600">Descrição da Despesa</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required placeholder="Ex: Aluguel do Estabelecimento ou Conta de Gás">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4 mb-3">
                                <label for="tipo_custo" class="form-label fw-600">Classificação</label>
                                <select class="form-select" id="tipo_custo" name="tipo_custo" required>
                                    <option value="fixo">Custo Fixo (Entra no rateio)</option>
                                    <option value="variavel">Custo Variável</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="valor" class="form-label fw-600">Valor (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="valor" name="valor" required placeholder="0.00">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="status_pagamento" class="form-label fw-600">Status do Pagamento</label>
                                <select class="form-select" id="status_pagamento" name="status_pagamento" required>
                                    <option value="pendente">Pendente / A Pagar</option>
                                    <option value="pago">Pago</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="data_vencimento" class="form-label fw-600">Data Vencimento (Opcional)</label>
                                <input type="date" class="form-control" id="data_vencimento" name="data_vencimento">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="data_pagamento" class="form-label fw-600">Data Pagamento (Opcional)</label>
                                <input type="date" class="form-control" id="data_pagamento" name="data_pagamento">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4 mb-3">
                                <label for="mes" class="form-label fw-600">Mês de Referência</label>
                                <select class="form-select" id="mes" name="mes" required>
                                    <option value="1">Janeiro</option>
                                    <option value="2">Fevereiro</option>
                                    <option value="3">Março</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Maio</option>
                                    <option value="6">Junho</option>
                                    <option value="7">Julho</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="ano" class="form-label fw-600">Ano</label>
                                <input type="number" class="form-control" id="ano" name="ano" required min="2020" max="2030">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="metodo_pagamento_id" class="form-label fw-600">Cartão / Conta Usada</label>
                                <select class="form-select" id="metodo_pagamento_id" name="metodo_pagamento_id">
                                    <option value="">Não Vinculado</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">Salvar Despesa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/custos/index.js"></script>
