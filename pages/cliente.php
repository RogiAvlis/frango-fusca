<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Clientes";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users me-2"></i>Gerenciar Clientes</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCliente">
            <i class="fas fa-plus me-2"></i> Novo Cliente
        </button>
    </div>

    <!-- Tabela para listar os dados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-clientes" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Nome</th>
                        <th>Telefone</th>
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
<div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalClienteLabel">Cadastrar / Editar Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-cliente">
                    <input type="hidden" id="cliente_id" name="id">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" maxlength="100" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" maxlength="20">
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
                <button type="submit" class="btn btn-primary" form="form-cliente">
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
<script src="../assets/js/cliente/tabela.js"></script>
<script src="../assets/js/cliente/cadastrar_editar.js"></script>
<script src="../assets/js/cliente/deletar.js"></script>
