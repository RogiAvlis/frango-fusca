<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Maquininhas de Cartão';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Maquininhas de Cartão</h2>
            <p class="text-muted small mb-0">Cadastre os terminais de pagamento (Stone, PagSeguro, Rede) e configure taxas por transação e mensalidades</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Adicionar Maquininha
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-maquinas" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Nome da Maquininha</th>
                            <th>Modalidade da Tarifa</th>
                            <th>Taxa / Tarifa por Venda</th>
                            <th>Mensalidade (R$)</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-nome">
                                <input type="text" list="datalist-filtro-nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-nome"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-tipo_taxa">
                                <input type="text" list="datalist-filtro-tipo_taxa" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-tipo_taxa"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-taxa">
                                <input type="text" list="datalist-filtro-taxa" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-taxa"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-mensalidade_valor">
                                <input type="text" list="datalist-filtro-mensalidade_valor" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-mensalidade_valor"></datalist>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Maquininha -->
    <div class="modal fade" id="modal-maquina" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-maquina-label">Adicionar Maquininha</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-maquina">
                    <div class="modal-body p-4">
                        <input type="hidden" id="maquinaId" name="id">
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-600">Nome / Identificação da Maquininha</label>
                            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Stone Crédito À Vista">
                        </div>
                        <div class="mb-3">
                            <label for="tipo_taxa" class="form-label fw-600">Modalidade de Cobrança</label>
                            <select class="form-select" id="tipo_taxa" name="tipo_taxa">
                                <option value="percentual">Percentual (% cobrado sobre cada venda)</option>
                                <option value="fixo_por_venda">Valor Fixo (R$ cobrado sobre cada venda)</option>
                                <option value="mensalidade">Apenas Mensalidade Fixa (R$/mês)</option>
                            </select>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="taxa" class="form-label fw-600">Taxa por Venda (% ou R$)</label>
                                <input type="number" step="0.01" class="form-control" id="taxa" name="taxa" placeholder="0.00" value="0.00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mensalidade_valor" class="form-label fw-600">Mensalidade (R$/mês)</label>
                                <input type="number" step="0.01" class="form-control" id="mensalidade_valor" name="mensalidade_valor" placeholder="0.00" value="0.00">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-600">Descrição / Detalhes do Contrato</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="2" placeholder="Observações sobre plano, operadora, modelo"></textarea>
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
<script type="module" src="../../assets/js/maquinas_venda/index.js"></script>
