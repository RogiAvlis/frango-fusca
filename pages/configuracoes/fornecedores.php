<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Fornecedores';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Gestão de Fornecedores</h2>
            <p class="text-muted small mb-0">Cadastre os fornecedores de frango, temperos, bebidas, embalagens e insumos da loja</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-adicionar">
            <i class="fas fa-plus-circle me-2"></i> Adicionar Fornecedor
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-fornecedores" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Razão Social / Nome</th>
                            <th>CNPJ / CPF</th>
                            <th>Telefone / Whats</th>
                            <th>E-mail</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-nome">
                                <input type="text" list="datalist-filtro-nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-nome"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-cnpj_cpf">
                                <input type="text" list="datalist-filtro-cnpj_cpf" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-cnpj_cpf"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-telefone">
                                <input type="text" list="datalist-filtro-telefone" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-telefone"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-email">
                                <input type="text" list="datalist-filtro-email" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-email"></datalist>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar/Editar Fornecedor -->
    <div class="modal fade" id="modal-fornecedor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning" id="modal-fornecedor-label">Adicionar Fornecedor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-fornecedor">
                    <div class="modal-body p-4">
                        <input type="hidden" id="fornecedorId" name="id">
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-600">Razão Social / Nome Fantasia</label>
                            <input type="text" class="form-control" id="nome" name="nome" required placeholder="Ex: Avícola Aurora Alimentos">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="cnpj_cpf" class="form-label fw-600">CNPJ ou CPF</label>
                                <input type="text" class="form-control" id="cnpj_cpf" name="cnpj_cpf" placeholder="00.000.000/0001-00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label fw-600">Telefone / WhatsApp</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-600">E-mail de Contato</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="vendas@fornecedor.com.br">
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label fw-600">Endereço Completo</label>
                            <textarea class="form-control" id="endereco" name="endereco" rows="2" placeholder="Rua, número, bairro, cidade - UF"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">Salvar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/fornecedores/index.js"></script>
