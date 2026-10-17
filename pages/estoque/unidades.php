<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Unidades de Medida';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Unidades de Medida</h2>
            <p class="text-muted small mb-0">Cadastre e gerencie as unidades de medida para produtos e insumos (UN, KG, L, etc.)</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Adicionar Unidade
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-unidades" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Sigla</th>
                            <th>Nome Completo</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-sigla">
                                <input type="text" list="datalist-filtro-sigla" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-sigla"></datalist>
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

    <!-- Modal Adicionar/Editar Unidade -->
    <div class="modal fade" id="modal-unidade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-unidade-label">Adicionar Unidade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-unidade">
                    <div class="modal-body p-4">
                        <input type="hidden" id="unidadeId" name="id">
                        <div class="mb-3">
                            <label for="sigla" class="form-label fw-600">Sigla (Ex: UN, KG, L)</label>
                            <input type="text" class="form-control text-uppercase" id="sigla" name="sigla" maxlength="10" required placeholder="Ex: KG">
                        </div>
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-600">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Quilograma">
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
<script type="module" src="../../assets/js/unidades/index.js"></script>
