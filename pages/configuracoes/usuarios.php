<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Equipe & Profissionais';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Gestão de Equipe & Profissionais</h2>
            <p class="text-muted small mb-0">Gerencie a equipe da loja e envie convites para novos funcionários cadastraram suas senhas</p>
        </div>
        <button type="button" class="btn btn-warning fw-bold shadow-sm px-4 text-dark" id="btn-convidar">
            <i class="fas fa-paper-plane me-2"></i> Convidar Profissional
        </button>
    </div>

    <!-- Tabela de Profissionais Ativos -->
    <div class="card border-0 shadow-sm overflow-hidden mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-users me-2"></i> Profissionais Ativos</h6>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-usuarios" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>Nome Completo</th>
                            <th>E-mail</th>
                            <th>Perfil / Função</th>
                            <th class="text-center">Ações</th>
                        </tr>
                        <tr id="filtro">
                            <th class="permitir-filtro" id="filtro-nome">
                                <input type="text" list="datalist-filtro-nome" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-nome"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-email">
                                <input type="text" list="datalist-filtro-email" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-email"></datalist>
                            </th>
                            <th class="permitir-filtro" id="filtro-perfil">
                                <input type="text" list="datalist-filtro-perfil" class="form-control form-control-sm" placeholder="Filtrar..." />
                                <datalist id="datalist-filtro-perfil"></datalist>
                            </th>
                            <th class="text-center">-</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabela de Convites Pendentes -->
    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-light py-3">
            <h6 class="fw-bold mb-0 text-secondary"><i class="fas fa-clock me-2"></i> Convites Pendentes de Ativação</h6>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="tabela-convites" class="table table-hover align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th>E-mail Convidado</th>
                            <th>Código do Convite</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Gerar Convite -->
    <div class="modal fade" id="modal-convite" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white py-3">
                    <h5 class="modal-title fw-bold text-warning">Gerar Convite de Acesso</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-convite">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="conviteEmail" class="form-label fw-600">E-mail do Profissional</label>
                            <input type="email" class="form-control" id="conviteEmailInput" name="email" required placeholder="funcionario@frangofusca.com.br">
                        </div>
                        <div class="mb-3">
                            <label for="convitePerfil" class="form-label fw-600">Perfil / Nível de Acesso</label>
                            <select class="form-select" id="convitePerfil" name="perfil" required>
                                <option value="vendedor">Vendedor (Apenas Vendas/PDV)</option>
                                <option value="caixa">Caixa (Vendas + Caixa)</option>
                                <option value="gerente">Gerente (Gestão Operacional)</option>
                                <option value="admin">Administrador (Acesso Total)</option>
                            </select>
                        </div>

                        <div id="conviteResultado" class="alert alert-success d-none mt-3 p-3">
                            <h6 class="fw-bold mb-1"><i class="fas fa-check-circle me-1"></i> Convite Gerado!</h6>
                            <p class="small mb-2">Envie o código ou o link abaixo para o profissional:</p>
                            <p class="mb-2"><strong>Código:</strong> <span id="resultadoCodigo" class="fs-5 text-primary"></span></p>
                            <div class="input-group">
                                <input type="text" id="resultadoLink" class="form-control form-control-sm" readonly>
                                <button type="button" class="btn btn-sm btn-dark btn-copiar"><i class="fas fa-copy me-1"></i> Copiar Link</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light px-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">Gerar Código & Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/usuarios/index.js"></script>
