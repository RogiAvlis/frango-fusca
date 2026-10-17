<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Métodos de Pagamento';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Métodos de Pagamento</h2>
            <p class="text-muted small mb-0">Cadastre os meios de pagamento (Pix, Cartão de Crédito, Dinheiro, etc.) e vincule aos cartões e contas da loja.</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Adicionar Método
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-metodos" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Nome do Método</th>
                            <th>Banco Vinculado</th>
                            <th>Agência</th>
                            <th>Conta</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-nome">
                                <input type="text" list="datalist-filtro-nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-nome"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-banco_nome">
                                <input type="text" list="datalist-filtro-banco_nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-banco_nome"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-agencia">
                                <input type="text" list="datalist-filtro-agencia" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-agencia"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-conta">
                                <input type="text" list="datalist-filtro-conta" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-conta"></datalist>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Método -->
    <div class="modal fade" id="modal-metodo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-metodo-label">Adicionar Método de Pagamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-metodo">
                    <div class="modal-body p-4">
                        <input type="hidden" id="metodoId" name="id">
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-600">Nome do Método (Obrigatório)</label>
                            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Cartão Itaú Crédito Empresarial">
                        </div>
                        <div class="mb-3">
                            <label for="banco_id" class="form-label fw-600">Banco Vinculado (Opcional)</label>
                            <select class="form-select" id="banco_id" name="banco_id">
                                <option value="">Nenhum Banco / Dinheiro Vivo</option>
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="agencia" class="form-label fw-600">Agência (Opcional)</label>
                                <input type="text" class="form-control" id="agencia" name="agencia" placeholder="Ex: 1234">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="conta" class="form-label fw-600">Conta (Opcional)</label>
                                <input type="text" class="form-control" id="conta" name="conta" placeholder="Ex: 56789-0">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">Salvar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/metodos_pagamento/index.js"></script>
