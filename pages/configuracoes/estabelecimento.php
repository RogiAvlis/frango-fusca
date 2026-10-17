<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Configurações da Loja';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0">Configurações do Estabelecimento</h2>
            <p class="text-muted small mb-0">Parametrização dos dados cadastrais da loja, dados do responsável e contatos institucionais</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-xl-6">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-store me-2"></i> Perfil da Loja / Empresa</h6>
                </div>
                <div class="card-body p-4">
                    <div id="estAlert" class="alert d-none mb-3 py-2 small" role="alert"></div>

                    <form id="form-estabelecimento">
                        <div class="mb-3">
                            <label for="estNome" class="form-label fw-600">Nome Fantasia da Loja</label>
                            <input type="text" class="form-control" id="estNome" name="nome" required placeholder="Ex: Frango do Fusca - Unidade Matriz">
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="estCnpj" class="form-label fw-600">CNPJ / CPF</label>
                                <input type="text" class="form-control" id="estCnpj" name="cnpj" placeholder="00.000.000/0001-00">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estTelefone" class="form-label fw-600">Telefone / Whats da Loja</label>
                                <input type="text" class="form-control" id="estTelefone" name="telefone_contato" placeholder="(00) 00000-0000">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estEmail" class="form-label fw-600">E-mail Comercial</label>
                            <input type="email" class="form-control" id="estEmail" name="email" placeholder="contato@loja.com.br">
                        </div>

                        <div class="mb-3">
                            <label for="estEndereco" class="form-label fw-600">Endereço Físico</label>
                            <textarea class="form-control" id="estEndereco" name="endereco" rows="2" placeholder="Rua, Número, Bairro, Cidade - UF"></textarea>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-user-shield me-2 text-warning"></i> Dados do Responsável Legal</h6>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="estRespNome" class="form-label fw-600">Nome do Responsável</label>
                                <input type="text" class="form-control" id="estRespNome" name="nome_responsavel" placeholder="Ex: Igor Silva">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estRespTel" class="form-label fw-600">Telefone do Responsável</label>
                                <input type="text" class="form-control" id="estRespTel" name="telefone_responsavel" placeholder="(00) 90000-0000">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">
                                <i class="fas fa-save me-2"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
$(document).ready(function() {
    // Carregar dados atuais
    $.getJSON('../../api/estabelecimento.php?action=buscar', function(res) {
        if (res.success && res.data) {
            $('#estNome').val(res.data.nome);
            $('#estCnpj').val(res.data.cnpj);
            $('#estTelefone').val(res.data.telefone_contato);
            $('#estEmail').val(res.data.email);
            $('#estEndereco').val(res.data.endereco);
            $('#estRespNome').val(res.data.nome_responsavel);
            $('#estRespTel').val(res.data.telefone_responsavel);
        }
    });

    $('#form-estabelecimento').on('submit', function(e) {
        e.preventDefault();
        const alertBox = $('#estAlert');
        alertBox.removeClass('alert-success alert-danger d-none').text('');

        $.post('../../api/estabelecimento.php?action=salvar', $(this).serialize(), function(res) {
            if (res.success) {
                alertBox.addClass('alert-success').removeClass('d-none').text(res.message);
            } else {
                alertBox.addClass('alert-danger').removeClass('d-none').text(res.message || 'Erro ao salvar.');
            }
        }, 'json');
    });
});
</script>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
