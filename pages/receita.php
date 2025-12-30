<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Receitas";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-receipt me-2"></i>Gerenciar Receitas</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReceita">
            <i class="fas fa-plus me-2"></i> Nova Receita
        </button>
    </div>

    <!-- Tabela para listar os dados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-receitas" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Produto Principal</th>
                        <th>Ingrediente</th>
                        <th>Qtd. Necessária</th>
                        <th>Unidade Medida</th>
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
<div class="modal fade" id="modalReceita" tabindex="-1" aria-labelledby="modalReceitaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReceitaLabel">Cadastrar / Editar Receita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-receita">
                    <input type="hidden" id="receita_id" name="id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="produto_principal_id" class="form-label">Produto Principal *</label>
                            <select class="form-select" id="produto_principal_id" name="produto_principal_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="produto_ingrediente_id" class="form-label">Ingrediente *</label>
                            <select class="form-select" id="produto_ingrediente_id" name="produto_ingrediente_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quantidade_necessaria" class="form-label">Quantidade Necessária *</label>
                            <input type="number" class="form-control" id="quantidade_necessaria" name="quantidade_necessaria" step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="unidade_medida_id" class="form-label">Unidade de Medida *</label>
                            <select class="form-select" id="unidade_medida_id" name="unidade_medida_id" required>
                                <!-- Opções carregadas via AJAX -->
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status_registro" class="form-label">Status</label>
                        <select class="form-select" id="status_registro" name="status_registro" required>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="form-receita">
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
<script src="../assets/js/receita/tabela.js"></script>
<script src="../assets/js/receita/selects.js"></script>
<script src="../assets/js/receita/cadastrar_editar.js"></script>
<script src="../assets/js/receita/deletar.js"></script>
