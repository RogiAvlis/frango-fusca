<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/config.php';

$codigoParam = $_GET['codigo'] ?? '';
$page_title = 'Ativar Convite';
$hide_navbar = true;
include __DIR__ . '/templates/header.php';
?>

<div class="d-flex align-items-center justify-content-center min-vh-100 bg-dark text-white px-3" style="background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);">
    <div class="card border-0 shadow-lg p-4 p-md-5 bg-white text-dark rounded-4" style="max-width: 480px; width: 100%;">
        <div class="text-center mb-4">
            <i class="fas fa-envelope-open-text fs-1 text-warning mb-2"></i>
            <h4 class="fw-800 text-dark mb-1">Ativação de Convite</h4>
            <p class="text-muted small">Informe seu e-mail, o código do convite e defina sua senha para acessar o sistema.</p>
        </div>

        <div id="conviteAlert" class="alert alert-danger d-none py-2 small" role="alert"></div>

        <form id="formConvite" method="POST" action="<?php echo BASE_URL; ?>/api/auth.php?action=aceitar_convite">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="conviteEmail" name="email" placeholder="Seu e-mail" required autocomplete="email">
                <label for="conviteEmail"><i class="fas fa-envelope me-1 text-muted"></i> Seu E-mail</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="conviteCodigo" name="codigo" value="<?php echo htmlspecialchars($codigoParam); ?>" placeholder="Código do convite" required>
                <label for="conviteCodigo"><i class="fas fa-key me-1 text-muted"></i> Código de Confirmação</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="conviteNome" name="nome" placeholder="Seu nome completo" required>
                <label for="conviteNome"><i class="fas fa-user me-1 text-muted"></i> Seu Nome Completo</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="conviteSenha" name="senha" placeholder="Crie sua senha" required autocomplete="new-password" minlength="6">
                <label for="conviteSenha"><i class="fas fa-lock me-1 text-muted"></i> Crie sua Senha</label>
            </div>

            <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm mb-3 text-dark">
                <i class="fas fa-check-circle me-2"></i> Ativar Conta e Cadastrar Senha
            </button>

            <div class="text-center">
                <a href="<?php echo BASE_URL; ?>/login.php" class="text-decoration-none small text-muted">
                    Já possui conta ativada? <strong class="text-primary">Fazer Login</strong>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formConvite').on('submit', function(e) {
        e.preventDefault();
        const alertBox = $('#conviteAlert');
        const btnSubmit = $(this).find('button[type="submit"]');

        alertBox.removeClass('alert-success').addClass('alert-danger d-none').text('');
        btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Processando...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alertBox.removeClass('alert-danger d-none').addClass('alert-success').text(res.message);
                    setTimeout(function() {
                        window.location.href = '<?php echo BASE_URL; ?>/login.php';
                    }, 2000);
                } else {
                    alertBox.removeClass('d-none').text(res.message || 'Erro ao aceitar convite.');
                    btnSubmit.prop('disabled', false).html('<i class="fas fa-check-circle me-2"></i> Ativar Conta e Cadastrar Senha');
                }
            },
            error: function() {
                alertBox.removeClass('d-none').text('Falha na comunicação com o servidor.');
                btnSubmit.prop('disabled', false).html('<i class="fas fa-check-circle me-2"></i> Ativar Conta e Cadastrar Senha');
            }
        });
    });
});
</script>

<?php include __DIR__ . '/templates/footer.php'; ?>
