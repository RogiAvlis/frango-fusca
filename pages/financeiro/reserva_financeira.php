<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Reserva Financeira (Cofrinho)';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0"><i class="fas fa-piggy-bank text-warning me-2"></i> Reserva Financeira (Cofrinho)</h2>
            <p class="text-muted small mb-0">Crie metas de reserva financeira, emergência ou investimentos futuros para a sua loja</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Criar Nova Meta de Reserva
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-reservas" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Descrição / Objetivo</th>
                            <th>Meta de Reserva (R$)</th>
                            <th>Saldo Acumulado (R$)</th>
                            <th>Progresso da Meta</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-descricao">
                                <input type="text" list="datalist-filtro-descricao" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-descricao"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-meta_valor">
                                <input type="text" list="datalist-filtro-meta_valor" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-meta_valor"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-saldo_atual">
                                <input type="text" list="datalist-filtro-saldo_atual" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-saldo_atual"></datalist>
                            </th>
                            <th class="text-center">-</th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Reserva -->
    <div class="modal fade" id="modal-reserva" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-reserva-label">Nova Reserva / Cofrinho</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-reserva">
                    <div class="modal-body p-4">
                        <input type="hidden" id="reservaId" name="id">

                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-600">Descrição / Objetivo da Reserva</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required placeholder="Ex: Fundo de Reserva para 13º e Férias">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="meta_valor" class="form-label fw-600">Meta Desejada (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="meta_valor" name="meta_valor" placeholder="Ex: 10000.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="saldo_atual" class="form-label fw-600">Saldo Atual (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="saldo_atual" name="saldo_atual" placeholder="0.00" value="0.00">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="data_previsao" class="form-label fw-600">Data Limite / Alvo (Opcional)</label>
                            <input type="date" class="form-control" id="data_previsao" name="data_previsao">
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">Salvar Reserva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Aporte / Resgate -->
    <div class="modal fade" id="modal-aporte" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white py-3">
                    <h5 class="modal-title fw-bold">Aporte ou Resgate no Cofrinho</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-aporte">
                    <div class="modal-body p-4 text-center">
                        <input type="hidden" id="aporteReservaId" name="id">
                        <p class="text-muted mb-1">Reserva selecionada:</p>
                        <h5 class="fw-bold text-dark mb-4" id="aporte-nome-reserva"></h5>

                        <div class="mb-3 text-start">
                            <label for="aporteValor" class="form-label fw-600">Valor da Movimentação (R$)</label>
                            <input type="number" step="0.01" class="form-control form-control-lg text-center fw-bold" id="aporteValor" name="valor" required placeholder="Digite o valor (ex: +500.00 ou -200.00)">
                            <div class="form-text small text-center">Use valores **positivos** para guardar dinheiro (aporte) ou **negativos** para retirar (resgate).</div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm">Confirmar Movimentação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/reserva_financeira/index.js"></script>
