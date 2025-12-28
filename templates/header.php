<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php'; 
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Frango do Fusca' : 'Frango do Fusca'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>/pages/dashboard_vendas.php">
            <img src="<?php echo BASE_URL; ?>/assets/img/frango_fusca_alternativa_3_sem_fundo.png" alt="Logo" style="height: 40px;">
            Frango do Fusca
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cogs me-2"></i>Cadastros
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/cliente.php">Clientes</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/fornecedor.php">Fornecedores</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/produto.php">Produtos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/unidade_medida.php">Unidades de Medida</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/metodo_pagamento.php">Métodos de Pagamento</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/ambiente_venda.php">Ambientes de Venda</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/maquina_venda.php">Máquinas de Venda</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/usuario.php">Usuários</a></li>
                    </ul>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/pages/venda.php"><i class="fas fa-shopping-cart me-2"></i>Vendas</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/pages/custo_mensal.php"><i class="fas fa-file-invoice-dollar me-2"></i>Custos</a>
                </li>
                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRelatorios" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-chart-bar me-2"></i>Relatórios
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownRelatorios">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/dashboard_vendas.php">Dashboard de Vendas</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/simulador_preco.php">Simulador de Preços</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>