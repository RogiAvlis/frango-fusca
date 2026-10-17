<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Canais de Venda / Plataformas';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Canais de Venda & Plataformas</h2>
            <p class="text-muted small mb-0">Cadastre os canais de atendimento (Balcão, WhatsApp, iFood) e configure as taxas de comissão por canal</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Adicionar Canal
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-ambientes" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Nome do Canal</th>
                            <th>Descrição / Detalhes</th>
                            <th>Taxa / Comissão (%)</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-nome">
                                <input type="text" list="datalist-filtro-nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-nome"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-descricao">
                                <input type="text" list="datalist-filtro-descricao" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-descricao"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-taxa">
                                <input type="text" list="datalist-filtro-taxa" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-taxa"></datalist>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Canal -->
    <div class="modal fade" id="modal-ambiente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-ambiente-label">Adicionar Canal de Venda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-ambiente">
                    <div class="modal-body p-4">
                        <input type="hidden" id="ambienteId" name="id">
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-600">Nome do Canal (Ex: iFood Delivery)</label>
                            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: iFood Delivery">
                        </div>
                        <div class="mb-3">
                            <label for="taxa" class="form-label fw-600">Taxa de Comissão (%)</label>
                            <input type="number" step="0.01" class="form-control" id="taxa" name="taxa" placeholder="0.00" value="0.00">
                            <div class="form-text small">Porcentagem cobrada sobre a venda pelo canal/plataforma.</div>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-600">Descrição / Observações</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="2" placeholder="Detalhes operacionais do canal"></textarea>
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
<script type="module" src="../../assets/js/ambientes_venda/index.js"></script>
