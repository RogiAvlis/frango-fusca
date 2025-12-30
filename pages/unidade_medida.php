<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Unidade de Medida";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-ruler-combined me-2"></i>Gerenciar Unidade de Medida</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUnidadeMedida">
            <i class="fas fa-plus me-2"></i> Nova Unidade de Medida
        </button>
    </div>

    <!-- Tabela para listar os dados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-unidades" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sigla</th>
                        <th>Nome</th>
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
<div class="modal fade" id="modalUnidadeMedida" tabindex="-1" aria-labelledby="modalUnidadeMedidaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUnidadeMedidaLabel">Cadastrar / Editar Unidade de Medida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-unidade-medida">
                    <!-- Campo oculto para o ID em caso de edição -->
                    <input type="hidden" id="unidade_medida_id" name="id">

                    <!-- Campos do formulário -->
                    <div class="mb-3">
                        <label for="sigla" class="form-label">Sigla:</label>
                        <input type="text" class="form-control" id="sigla" name="sigla" maxlength="5" required>
                    </div>

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" maxlength="50" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="form-unidade-medida">
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
<script src="../assets/js/unidade_medida/tabela.js"></script>
<script src="../assets/js/unidade_medida/cadastrar_editar.js"></script>
<script src="../assets/js/unidade_medida/deletar.js"></script>
