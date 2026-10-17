<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Fichas Técnicas (Receitas)';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Fichas Técnicas & Composição de Produtos</h2>
            <p class="text-muted small mb-0">Configure os ingredientes e quantidades de cada produto para baixa automática de estoque na venda</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-book-open me-2"></i> Montar / Editar Ficha Técnica</h6>
                </div>
                <div class="card-body p-4">
                    <form id="form-receita">
                        <div class="mb-4">
                            <label for="select-produto-principal" class="form-label fw-bold fs-6">Selecione o Produto Final (Ex: Frango Assado)</label>
                            <select class="form-select form-select-lg" id="select-produto-principal" name="produto_principal_id" required>
                                <option value="">Escolha um Produto...</option>
                            </select>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-cubes me-2 text-warning"></i> Ingredientes & Insumos Utilizados</h6>
                            <button type="button" class="btn btn-sm btn-outline-dark fw-bold" id="btn-add-ingrediente-linha">
                                <i class="fas fa-plus me-1"></i> Adicionar Ingrediente
                            </button>
                        </div>

                        <div id="container-linhas-ingredientes" class="mb-4">
                            <!-- Linhas inseridas via JS -->
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning fw-bold text-dark px-5 shadow-sm">
                                <i class="fas fa-save me-2"></i> Salvar Ficha Técnica Oficial
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/receitas/index.js"></script>
