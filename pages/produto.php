<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Produtos";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-boxes me-2"></i>Gerenciar Produtos</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProduto">
            <i class="fas fa-plus me-2"></i> Novo Produto
        </button>
    </div>

    <!-- Tabela para listar os dados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-produtos" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Nome</th>
                        <th>Custo</th>
                        <th>Venda</th>
                        <th>Qtd.</th>
                        <th>Unidade</th>
                        <th>Fornecedor</th>
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
<div class="modal fade" id="modalProduto" tabindex="-1" aria-labelledby="modalProdutoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProdutoLabel">Cadastrar / Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-produto">
                    <input type="hidden" id="produto_id" name="id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="nome" name="nome" maxlength="100" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_registro" class="form-label">Status</label>
                            <select class="form-select" id="status_registro" name="status_registro" required>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="preco_custo" class="form-label">Preço de Custo *</label>
                            <input type="number" class="form-control" id="preco_custo" name="preco_custo" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="preco_venda" class="form-label">Preço de Venda *</label>
                            <input type="number" class="form-control" id="preco_venda" name="preco_venda" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="quantidade_comprada" class="form-label">Qtd. Comprada *</label>
                            <input type="number" class="form-control" id="quantidade_comprada" name="quantidade_comprada" min="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="unidade_medida_id" class="form-label">Unidade de Medida *</label>
                            <select class="form-select" id="unidade_medida_id" name="unidade_medida_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fornecedor_id" class="form-label">Fornecedor *</label>
                            <select class="form-select" id="fornecedor_id" name="fornecedor_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="form-produto">
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
<script src="../assets/js/produto/tabela.js"></script>
<script src="../assets/js/produto/selects.js"></script>
<script src="../assets/js/produto/cadastrar_editar.js"></script>
<script src="../assets/js/produto/deletar.js"></script>
