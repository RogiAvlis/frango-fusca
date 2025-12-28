<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$page_title = "Usuários";
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users me-2"></i>Gerenciar Usuários</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="fas fa-plus me-2"></i> Novo Usuário
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-usuarios" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 120px;">Ação</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Cadastro e Edição -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUsuarioLabel">Cadastrar / Editar Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-usuario">
                    <input type="hidden" id="usuario_id" name="id">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail *</label>
                        <input type="email" class="form-control" id="email" name="email" maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label for="senha" class="form-label">Senha *</label>
                        <input type="password" class="form-control" id="senha" name="senha" minlength="6">
                        <small class="form-text text-muted">Deixe em branco para manter a senha atual.</small>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_senha" class="form-label">Confirmar Senha *</label>
                        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" minlength="6">
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
                <button type="submit" class="btn btn-primary" form="form-usuario">
                    <i class="fas fa-save me-2"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../templates/footer.php';
?>

<!-- Scripts específicos da página -->
<script src="../assets/js/usuario/tabela.js"></script>
<script src="../assets/js/usuario/cadastrar_editar.js"></script>
<script src="../assets/js/usuario/deletar.js"></script>
