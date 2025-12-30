<?php
require_once __DIR__ . '/../src/core/verificar_sessao.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$page_title = "Custos Mensais";
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-file-invoice-dollar me-2"></i>Gerenciar Custos Mensais</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCustoMensal">
            <i class="fas fa-plus me-2"></i> Novo Custo Mensal
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tabela-custos" class="table table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição</th>
                        <th>Valor</th>
                        <th>Data Pag.</th>
                        <th>Tipo</th>
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
<div class="modal fade" id="modalCustoMensal" tabindex="-1" aria-labelledby="modalCustoMensalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCustoMensalLabel">Cadastrar / Editar Custo Mensal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-custo-mensal">
                    <input type="hidden" id="custo_mensal_id" name="id">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="descricao" class="form-label">Descrição *</label>
                            <input type="text" class="form-control" id="descricao" name="descricao" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="valor" class="form-label">Valor *</label>
                            <input type="number" class="form-control" id="valor" name="valor" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="data_pagamento" class="form-label">Data de Pagamento *</label>
                            <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="quantidade_parcela" class="form-label">Qtd. Parcelas</label>
                            <input type="number" class="form-control" id="quantidade_parcela" name="quantidade_parcela" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="mes" class="form-label">Mês de Referência *</label>
                            <input type="number" class="form-control" id="mes" name="mes" min="1" max="12" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ano" class="form-label">Ano de Referência *</label>
                            <input type="number" class="form-control" id="ano" name="ano" min="2000" max="2100" required>
                        </div>
                         <div class="col-md-4 mb-3">
                            <label for="tipo_custo" class="form-label">Tipo de Custo *</label>
                            <select class="form-select" id="tipo_custo" name="tipo_custo" required>
                                <option value="fixo">Fixo</option>
                                <option value="variavel">Variável</option>
                            </select>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="status_pagamento" class="form-label">Status do Pagamento</label>
                            <select class="form-select" id="status_pagamento" name="status_pagamento" required>
                                <option value="0">Não Pago</option>
                                <option value="1">Pago</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary" form="form-custo-mensal">
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
<script src="../assets/js/custo_mensal/tabela.js"></script>
<script src="../assets/js/custo_mensal/cadastrar_editar.js"></script>
<script src="../assets/js/custo_mensal/deletar.js"></script>
