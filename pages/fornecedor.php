<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Fornecedores";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-truck-moving me-2"></i>Gerenciar Fornecedores</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalFornecedor">
            <i class="fas fa-plus me-2"></i> Novo Fornecedor
        </button>
    </div>

    <!-- Tabela para listar os dados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-fornecedores" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Nome</th>
                        <th>CNPJ/CPF</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
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
<div class="modal fade" id="modalFornecedor" tabindex="-1" aria-labelledby="modalFornecedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFornecedorLabel">Cadastrar / Editar Fornecedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-fornecedor">
                    <input type="hidden" id="fornecedor_id" name="id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="nome" name="nome" maxlength="100" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cnpj_cpf" class="form-label">CNPJ/CPF</label>
                            <input type="text" class="form-control" id="cnpj_cpf" name="cnpj_cpf" maxlength="18">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" maxlength="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" maxlength="20">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="endereco" class="form-label">Endereço</label>
                        <textarea class="form-control" id="endereco" name="endereco" rows="3"></textarea>
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
                <button type="submit" class="btn btn-primary" form="form-fornecedor">
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
<script src="../assets/js/fornecedor/tabela.js"></script>
<script src="../assets/js/fornecedor/cadastrar_editar.js"></script>
<script src="../assets/js/fornecedor/deletar.js"></script>
