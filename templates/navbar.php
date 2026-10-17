<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center fw-bold text-warning" href="<?php echo BASE_URL; ?>/index.php">
            <i class="fas fa-drumstick-bite me-2 fs-4"></i>
            <span>Frango<span class="text-white">Fusca</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active fw-bold' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php">
                        <i class="fas fa-chart-pie me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'pdv.php' ? 'active fw-bold' : ''; ?>" href="<?php echo BASE_URL; ?>/pdv.php">
                        <i class="fas fa-cash-register me-1 text-success"></i> PDV / Vendas
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo in_array($currentPage, ['produtos.php', 'estoque.php', 'receitas.php', 'unidades.php']) ? 'active fw-bold' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-boxes me-1 text-info"></i> Estoque & Produtos
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/produtos.php"><i class="fas fa-box me-2"></i> Produtos & Combos</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/estoque.php"><i class="fas fa-truck-loading me-2"></i> Entrada de Estoque & Compras</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/receitas.php"><i class="fas fa-book-open me-2"></i> Fichas Técnicas (Receitas)</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/unidades.php"><i class="fas fa-balance-scale me-2"></i> Unidades de Medida</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'precificacao.php' ? 'active fw-bold' : ''; ?>" href="<?php echo BASE_URL; ?>/precificacao.php">
                        <i class="fas fa-calculator me-1 text-warning"></i> Precificação
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo in_array($currentPage, ['custos.php', 'reserva_financeira.php', 'metodos_pagamento.php', 'bancos.php']) ? 'active fw-bold' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-wallet me-1 text-primary"></i> Financeiro
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/custos.php"><i class="fas fa-file-invoice-dollar me-2"></i> Custos & Despesas</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/reserva_financeira.php"><i class="fas fa-piggy-bank me-2"></i> Reserva Financeira (Cofrinho)</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/metodos_pagamento.php"><i class="fas fa-credit-card me-2"></i> Métodos de Pagamento</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/bancos.php"><i class="fas fa-university me-2"></i> Bancos</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php echo in_array($currentPage, ['clientes.php', 'fornecedores.php', 'ambientes_venda.php', 'maquinas_venda.php', 'usuarios.php']) ? 'active fw-bold' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cogs me-1 text-secondary"></i> Cadastros
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/clientes.php"><i class="fas fa-users me-2"></i> Clientes</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/fornecedores.php"><i class="fas fa-building me-2"></i> Fornecedores</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/ambientes_venda.php"><i class="fas fa-store me-2"></i> Canais de Venda / Plataformas</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/maquinas_venda.php"><i class="fas fa-credit-card me-2"></i> Maquininhas de Cartão</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/usuarios.php"><i class="fas fa-user-shield me-2"></i> Equipe / Profissionais</a></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/estabelecimento.php"><i class="fas fa-cog me-2"></i> Configurações da Loja</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'relatorios.php' ? 'active fw-bold' : ''; ?>" href="<?php echo BASE_URL; ?>/relatorios.php">
                        <i class="fas fa-file-alt me-1"></i> Relatórios
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="btn btn-outline-light dropdown-toggle d-flex align-items-center btn-sm px-3 rounded-pill" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fs-5 me-2 text-warning"></i>
                        <span><?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li class="px-3 py-1 small text-muted">Loja: <?php echo htmlspecialchars($_SESSION['estabelecimento_nome'] ?? 'Frango Fusca'); ?></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="<?php echo BASE_URL; ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
