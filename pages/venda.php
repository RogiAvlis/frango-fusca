<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Vendas";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-shopping-cart me-2"></i>Gerenciar Vendas</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVenda">
            <i class="fas fa-plus me-2"></i> Nova Venda
        </button>
    </div>

    <!-- Tabela para listar os dados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-vendas" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Data Venda</th>
                        <th>Valor Total</th>
                        <th>Método Pagto.</th>
                        <th>Ambiente Venda</th>
                        <th class="text-center" style="width: 120px;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Os dados serão carregados aqui via JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Cadastro e Edição -->
<div class="modal fade" id="modalVenda" tabindex="-1" aria-labelledby="modalVendaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVendaLabel">Cadastrar / Editar Venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-venda">
                    <input type="hidden" id="venda_id" name="id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cliente_id" class="form-label">Cliente *</label>
                            <select class="form-select" id="cliente_id" name="cliente_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vendedor_id" class="form-label">Vendedor *</label>
                            <select class="form-select" id="vendedor_id" name="vendedor_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="data_venda" class="form-label">Data da Venda *</label>
                            <input type="datetime-local" class="form-control" id="data_venda" name="data_venda" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="valor_total" class="form-label">Valor Total *</label>
                            <input type="number" class="form-control" id="valor_total" name="valor_total" step="0.01" min="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="metodo_pagamento_id" class="form-label">Método de Pagamento *</label>
                            <select class="form-select" id="metodo_pagamento_id" name="metodo_pagamento_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ambiente_venda_id" class="form-label">Ambiente de Venda *</label>
                            <select class="form-select" id="ambiente_venda_id" name="ambiente_venda_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                    </div>
                </form>

                <hr class="my-4">

                <h4><i class="fas fa-boxes me-2"></i>Itens da Venda</h4>
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <form id="form-item-venda" class="row g-3 align-items-end">
                            <input type="hidden" id="item_venda_id" name="id">
                            <input type="hidden" id="item_venda_venda_id" name="venda_id">
                            <div class="col-md-5">
                                <label for="item_venda_produto_id" class="form-label">Produto *</label>
                                <select class="form-select" id="item_venda_produto_id" name="produto_id" required>
                                    <!-- Opções carregadas via AJAX -->
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="item_venda_quantidade" class="form-label">Quantidade *</label>
                                <input type="number" class="form-control" id="item_venda_quantidade" name="quantidade" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label for="item_venda_preco" class="form-label">Preço Unitário *</label>
                                <input type="number" class="form-control" id="item_venda_preco" name="preco_venda" step="0.01" min="0" required>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-success w-100" id="btn-add-item-venda" title="Adicionar Item">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <table id="tabela-itens-venda" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                            <th>Total</th>
                            <th class="text-center" style="width: 100px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Os dados serão carregados aqui via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="form-venda">
                    <i class="fas fa-save me-2"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<?php
// Inclui o rodapé que tem os scripts globais e fecha o body/html
require_once __DIR__ . '/../templates/footer.php';
?>

<!-- Scripts específicos da página -->
<script src="../assets/js/venda/tabela.js"></script>
<script src="../assets/js/venda/selects.js"></script>
<script src="../assets/js/venda/cadastrar_editar.js"></script>
<script src="../assets/js/venda/deletar.js"></script>
<script src="../assets/js/venda/itens.js"></script>
