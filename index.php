<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Login | Frango do Fusca</title>
</head>
<body class="login-page">

    <div class="login-card">
        <img src="/assets/img/frango_fusca_alternativa_3_sem_fundo.png" 
             alt="Logo Frango do Fusca" class="login-logo">
        
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php 
                    echo $_SESSION['login_error']; 
                    unset($_SESSION['login_error']); // Limpa o erro após exibir
                ?>
            </div>
        <?php endif; ?>

        <form action="auth.php" method="POST">
            <div class="my-2">
                <label class="form-label" for="email">E-mail</label>
                <input type="email" name="email" class="form-control" placeholder="Digite seu e-mail" autocomplete="off" required>
            </div>
            
            <div class="my-2">
                <label class="form-label" for="password">Senha</label>
                <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" autocomplete="off" required>
            </div>

            <div class="text-end my-2">
                <a href="#" class="text-muted small text-decoration-none">Esqueceu a senha?</a>
            </div>

            <button type="submit" class="btn-login">LOGIN</button>

            <div class="social-icons my-2">
                <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-google"></i></a>
                <a href="#" class="social-btn"><i class="fab fa-instagram"></i></a>
            </div>
        </form>
    </div>
</body>
</html>