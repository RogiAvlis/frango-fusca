<?php
// Define um título para a página
$page_title = "Gerenciar [Nome da Entidade]";
// Inclui o cabeçalho
include_once 'header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-cogs me-2"></i>Gerenciar [Nome da Entidade]</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCadastro">
            <i class="fas fa-plus me-2"></i> Novo(a) [Nome da Entidade]
        </button>
    </div>

    <!-- Tabela para listar os dados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-dados" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <!-- Colunas da tabela serão definidas aqui -->
                        <th>ID</th>
                        <th>Nome</th>
                        <!-- Adicionar mais colunas conforme necessário -->
                        <th class="text-center" style="width: 120px;">Ações</th>
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
<div class="modal fade" id="modalCadastro" tabindex="-1" aria-labelledby="modalCadastroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCadastroLabel">Cadastrar / Editar [Nome da Entidade]</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-cadastro">
                    <!-- Campo oculto para o ID em caso de edição -->
                    <input type="hidden" id="campo-id" name="id">

                    <!-- Campos do formulário -->
                    <div class="mb-3">
                        <label for="campo-nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" id="campo-nome" name="nome" required>
                    </div>

                    <!-- Adicionar mais campos conforme necessário -->

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="form-cadastro">
                    <i class="fas fa-save me-2"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<?php
// Inclui o rodapé
include_once 'footer.php';
?>
