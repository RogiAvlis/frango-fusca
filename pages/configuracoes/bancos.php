<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Instituições Bancárias';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Instituições Bancárias</h2>
            <p class="text-muted small mb-0">Gerencie a lista de bancos vinculados aos métodos de pagamento (Itaú, Bradesco, Nubank, etc.)</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Adicionar Banco
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-bancos" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Código Febraban</th>
                            <th>Nome da Instituição</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-codigo">
                                <input type="text" list="datalist-filtro-codigo" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-codigo"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-nome">
                                <input type="text" list="datalist-filtro-nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-nome"></datalist>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Banco -->
    <div class="modal fade" id="modal-banco" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-banco-label">Adicionar Banco</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-banco">
                    <div class="modal-body p-4">
                        <input type="hidden" id="bancoId" name="id">
                        <div class="mb-3">
                            <label for="codigo" class="form-label fw-600">Código Febraban (Opcional)</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Ex: 341">
                        </div>
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-600">Nome do Banco / Instituição</label>
                            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Itaú Unibanco">
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
<script type="module" src="../../assets/js/bancos/index.js"></script>
