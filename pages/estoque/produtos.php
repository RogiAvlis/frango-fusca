<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Produtos, Insumos & Combos';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Produtos, Insumos & Combos</h2>
            <p class="text-muted small mb-0">Cadastre insumos (ingredientes), produtos acabados e combos, acompanhando o histórico de lotes de reposição</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Adicionar Produto
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-produtos" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Nome do Produto</th>
                            <th>Tipo</th>
                            <th>Preço Custo</th>
                            <th>Preço Venda</th>
                            <th>Estoque Atual</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-nome">
                                <input type="text" list="datalist-filtro-nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-nome"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-tipo_produto">
                                <input type="text" list="datalist-filtro-tipo_produto" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-tipo_produto"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-preco_custo">
                                <input type="text" list="datalist-filtro-preco_custo" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-preco_custo"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-preco_venda">
                                <input type="text" list="datalist-filtro-preco_venda" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-preco_venda"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-quantidade_estoque">
                                <input type="text" list="datalist-filtro-quantidade_estoque" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-quantidade_estoque"></datalist>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Produto -->
    <div class="modal fade" id="modal-produto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-produto-label">Adicionar Produto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-produto">
                    <div class="modal-body p-4">
                        <input type="hidden" id="produtoId" name="id">
                        
                        <div class="row g-3">
                            <div class="col-md-8 mb-3">
                                <label for="nome" class="form-label fw-600">Nome do Produto / Insumo</label>
                                <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Frango Cru Inteiro ou Frango Assado">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="tipo_produto" class="form-label fw-600">Tipo de Produto</label>
                                <select class="form-select" id="tipo_produto" name="tipo_produto" required>
                                    <option value="acabado">Produto Acabado (Venda Direta)</option>
                                    <option value="insumo">Insumo / Ingrediente (Ficha Técnica)</option>
                                    <option value="combo">Combo de Produtos</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4 mb-3">
                                <label for="preco_custo" class="form-label fw-600">Preço de Custo (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="preco_custo" name="preco_custo" placeholder="0.00" value="0.00">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="preco_venda" class="form-label fw-600">Preço de Venda (R$)</label>
                                <input type="number" step="0.01" class="form-control" id="preco_venda" name="preco_venda" placeholder="0.00" value="0.00">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estoque_minimo" class="form-label fw-600">Estoque Mínimo (Alerta)</label>
                                <input type="number" step="0.001" class="form-control" id="estoque_minimo" name="estoque_minimo" placeholder="5.000" value="5.000">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="unidade_medida_id" class="form-label fw-600">Unidade de Medida</label>
                                <select class="form-select" id="unidade_medida_id" name="unidade_medida_id" required>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fornecedor_id" class="form-label fw-600">Fornecedor Preferencial (Opcional)</label>
                                <select class="form-select" id="fornecedor_id" name="fornecedor_id">
                                    <option value="">Nenhum / Vários</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-600">Descrição / Detalhes</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="2" placeholder="Observações do produto"></textarea>
                        </div>

                        <!-- Sub-tabela de Histórico de Lotes do Produto -->
                        <div id="container-subtabela-lotes" class="mt-4 border-top pt-3 d-none">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-boxes me-2 text-warning"></i> Histórico de Lotes de Reposição / Entradas</h6>
                            <div class="table-responsive bg-light p-2 rounded border">
                                <table class="table table-sm table-striped align-middle mb-0 small">
                                    <thead>
                                        <tr class="table-dark">
                                            <th>Data da Entrada</th>
                                            <th>Quantidade</th>
                                            <th>Preço Custo Unit.</th>
                                            <th>Valor Total</th>
                                            <th>Fornecedor</th>
                                            <th>Método Pagamento</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabela-lotes-body"></tbody>
                                </table>
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
<script type="module" src="../../assets/js/produtos/index.js"></script>
