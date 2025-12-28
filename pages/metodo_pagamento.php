<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Define um título para a página
$page_title = "Método de Pagamento";
// Inclui o cabeçalho
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-credit-card me-2"></i>Gerenciar Método de Pagamento</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMetodoPagamento">
            <i class="fas fa-plus me-2"></i> Novo Método de Pagamento
        </button>
    </div>

    <!-- Tabela para listar os dados -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-metodos" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Banco</th>
                        <th>Agência</th>
                        <th>Conta</th>
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
<div class="modal fade" id="modalMetodoPagamento" tabindex="-1" aria-labelledby="modalMetodoPagamentoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalMetodoPagamentoLabel">Cadastrar / Editar Método de Pagamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-metodo-pagamento">
                    <input type="hidden" id="metodo_pagamento_id" name="id">

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="nome" name="nome" maxlength="50" required>
                    </div>

                    <div class="mb-3">
                        <label for="banco" class="form-label">Banco</label>
                        <input type="text" class="form-control" id="banco" name="banco" maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label for="agencia" class="form-label">Agência</label>
                        <input type="text" class="form-control" id="agencia" name="agencia" maxlength="20">
                    </div>

                    <div class="mb-3">
                        <label for="conta" class="form-label">Conta</label>
                        <input type="text" class="form-control" id="conta" name="conta" maxlength="20">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="form-metodo-pagamento">
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
<script src="../assets/js/metodo_pagamento/tabela.js"></script>
<script src="../assets/js/metodo_pagamento/cadastrar_editar.js"></script>
<script src="../assets/js/metodo_pagamento/deletar.js"></script>
