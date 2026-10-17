<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/config.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$page_title = 'Login';
$hide_navbar = true;
include __DIR__ . '/templates/header.php';
?>

<div class="d-flex align-items-center justify-content-center min-vh-100 bg-dark text-white px-3" style="background: linear-gradient(135deg, #1e1e1e 0%, #2a2a2a 100%);">
    <div class="card border-0 shadow-lg p-4 p-md-5 bg-white text-dark rounded-4" style="max-width: 420px; width: 100%;">
        <div class="text-center mb-4">
            <img src="<?php echo BASE_URL; ?>/assets/img/frango_fusca_alternativa_3_sem_fundo.png" alt="Frango Fusca" class="img-fluid mb-2" style="max-height: 100px;">
            <h4 class="fw-800 text-dark mb-1">Frango Fusca</h4>
            <p class="text-muted small">Sistema de Gestão Comercial Integrada</p>
        </div>

        <div id="loginAlert" class="alert alert-danger d-none py-2 small" role="alert"></div>

        <form id="formLogin" method="POST" action="<?php echo BASE_URL; ?>/api/auth.php?action=login">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="nome@exemplo.com" required autocomplete="email">
                <label for="loginEmail"><i class="fas fa-envelope me-1 text-muted"></i> E-mail</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="loginSenha" name="senha" placeholder="Sua senha" required autocomplete="current-password">
                <label for="loginSenha"><i class="fas fa-lock me-1 text-muted"></i> Senha</label>
            </div>

            <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold shadow-sm mb-3 text-dark">
                <i class="fas fa-sign-in-alt me-2"></i> Entrar no Sistema
            </button>

            <div class="text-center">
                <a href="<?php echo BASE_URL; ?>/aceitar_convite.php" class="text-decoration-none small text-muted">
                    Recebeu um convite? <strong class="text-primary">Ativar conta aqui</strong>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#formLogin').on('submit', function(e) {
        e.preventDefault();
        const alertBox = $('#loginAlert');
        const btnSubmit = $(this).find('button[type="submit"]');

        alertBox.addClass('d-none').text('');
        btnSubmit.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Entrando...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    window.location.href = '<?php echo BASE_URL; ?>/index.php';
                } else {
                    alertBox.removeClass('d-none').text(res.message || 'Erro ao efetuar login.');
                    btnSubmit.prop('disabled', false).html('<i class="fas fa-sign-in-alt me-2"></i> Entrar no Sistema');
                }
            },
            error: function() {
                alertBox.removeClass('d-none').text('Falha na comunicação com o servidor.');
                btnSubmit.prop('disabled', false).html('<i class="fas fa-sign-in-alt me-2"></i> Entrar no Sistema');
            }
        });
    });
});
</script>

<?php include __DIR__ . '/templates/footer.php'; ?>
