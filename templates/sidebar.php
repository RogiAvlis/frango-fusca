<?php
// Detecta a página atual pelo caminho relativo à raiz do projeto
$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
$rootPath   = str_replace('\\', '/', realpath(__DIR__ . '/..'));
$currentRelative = ltrim(str_replace($rootPath, '', $scriptPath), '/');
$currentPage = basename($scriptPath); // compatibilidade legada
?>
<aside class="sidebar bg-dark text-white d-flex flex-column flex-shrink-0 p-3 shadow-lg" style="width: 260px; min-height: 100vh;">
    <!-- Marca / Logo -->
    <a href="<?php echo BASE_URL; ?>/index.php" class="d-flex align-items-center mb-4 me-md-auto text-warning text-decoration-none px-2">
        <i class="fas fa-drumstick-bite fs-2 me-2"></i>
        <span class="fs-4 fw-800">Frango<span class="text-white">Fusca</span></span>
    </a>

    <!-- Card de Perfil do Usuário e Loja -->
    <div class="bg-secondary bg-opacity-25 p-3 rounded-3 mb-4 border border-secondary">
        <div class="d-flex align-items-center">
            <i class="fas fa-user-circle fs-3 text-warning me-2"></i>
            <div class="overflow-hidden">
                <h6 class="fw-bold text-white mb-0 text-truncate"><?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></h6>
                <small class="text-white-50 text-truncate d-block"><?php echo htmlspecialchars($_SESSION['estabelecimento_nome'] ?? 'Frango Fusca'); ?></small>
            </div>
        </div>
    </div>

    <!-- Menus de Navegação Lateral -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-1">
            <a href="<?php echo BASE_URL; ?>/index.php" class="nav-link text-white <?php echo $currentPage == 'index.php' && $currentRelative == 'index.php' ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="fas fa-chart-pie me-2 text-warning"></i> Painel do Dia
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="<?php echo BASE_URL; ?>/pages/analises/dashboard_analitico.php" class="nav-link text-white <?php echo $currentRelative == 'pages/analises/dashboard_analitico.php' ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="fas fa-chart-line me-2 text-primary"></i> Análises &amp; Decisões
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="<?php echo BASE_URL; ?>/pages/venda/pdv.php" class="nav-link text-white <?php echo $currentRelative == 'pages/venda/pdv.php' ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="fas fa-cash-register me-2 text-success"></i> Ponto de Venda
            </a>
        </li>

        <?php
        $pagesEstoque = ['pages/estoque/produtos.php','pages/estoque/estoque.php','pages/estoque/receitas.php','pages/estoque/unidades.php'];
        $estocActive  = in_array($currentRelative, $pagesEstoque);
        ?>
        <li class="nav-item mb-1">
            <a href="#menuEstoque" data-bs-toggle="collapse" class="nav-link text-white d-flex justify-content-between align-items-center <?php echo $estocActive ? 'active bg-secondary' : ''; ?>">
                <span><i class="fas fa-boxes me-2 text-info"></i> Estoque &amp; Produtos</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse <?php echo $estocActive ? 'show' : ''; ?> ms-3 mt-1" id="menuEstoque">
                <ul class="nav flex-column small">
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/estoque/produtos.php"  class="nav-link text-white-50 <?php echo $currentRelative=='pages/estoque/produtos.php'  ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-box me-2"></i> Produtos &amp; Combos</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/estoque/estoque.php"   class="nav-link text-white-50 <?php echo $currentRelative=='pages/estoque/estoque.php'   ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-truck-loading me-2"></i> Entradas &amp; Compras</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/estoque/receitas.php"  class="nav-link text-white-50 <?php echo $currentRelative=='pages/estoque/receitas.php'  ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-book-open me-2"></i> Fichas Técnicas</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/estoque/unidades.php"  class="nav-link text-white-50 <?php echo $currentRelative=='pages/estoque/unidades.php'  ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-balance-scale me-2"></i> Unidades de Medida</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item mb-1">
            <a href="<?php echo BASE_URL; ?>/pages/precificacao/precificacao.php" class="nav-link text-white <?php echo $currentRelative == 'pages/precificacao/precificacao.php' ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="fas fa-calculator me-2 text-warning"></i> Precificação Inteligente
            </a>
        </li>

        <?php
        $pagesFinanceiro = ['pages/financeiro/custos.php','pages/financeiro/reserva_financeira.php','pages/configuracoes/metodos_pagamento.php','pages/configuracoes/bancos.php'];
        $finActive = in_array($currentRelative, $pagesFinanceiro);
        ?>
        <li class="nav-item mb-1">
            <a href="#menuFinanceiro" data-bs-toggle="collapse" class="nav-link text-white d-flex justify-content-between align-items-center <?php echo $finActive ? 'active bg-secondary' : ''; ?>">
                <span><i class="fas fa-wallet me-2 text-primary"></i> Financeiro</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse <?php echo $finActive ? 'show' : ''; ?> ms-3 mt-1" id="menuFinanceiro">
                <ul class="nav flex-column small">
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/financeiro/custos.php"              class="nav-link text-white-50 <?php echo $currentRelative=='pages/financeiro/custos.php'              ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-file-invoice-dollar me-2"></i> Custos &amp; Despesas</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/financeiro/reserva_financeira.php"  class="nav-link text-white-50 <?php echo $currentRelative=='pages/financeiro/reserva_financeira.php'  ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-piggy-bank me-2"></i> Cofrinho &amp; Reservas</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/configuracoes/metodos_pagamento.php" class="nav-link text-white-50 <?php echo $currentRelative=='pages/configuracoes/metodos_pagamento.php' ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-credit-card me-2"></i> Meios de Pagamento</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/configuracoes/bancos.php"            class="nav-link text-white-50 <?php echo $currentRelative=='pages/configuracoes/bancos.php'            ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-university me-2"></i> Bancos</a></li>
                </ul>
            </div>
        </li>

        <?php
        $pagesCadastros = ['pages/configuracoes/clientes.php','pages/configuracoes/fornecedores.php','pages/configuracoes/ambientes_venda.php','pages/configuracoes/maquinas_venda.php','pages/configuracoes/usuarios.php','pages/configuracoes/estabelecimento.php'];
        $cadActive = in_array($currentRelative, $pagesCadastros);
        ?>
        <li class="nav-item mb-1">
            <a href="#menuCadastros" data-bs-toggle="collapse" class="nav-link text-white d-flex justify-content-between align-items-center <?php echo $cadActive ? 'active bg-secondary' : ''; ?>">
                <span><i class="fas fa-cogs me-2 text-secondary"></i> Cadastros &amp; Loja</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse <?php echo $cadActive ? 'show' : ''; ?> ms-3 mt-1" id="menuCadastros">
                <ul class="nav flex-column small">
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/configuracoes/clientes.php"        class="nav-link text-white-50 <?php echo $currentRelative=='pages/configuracoes/clientes.php'        ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-users me-2"></i> Clientes</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/configuracoes/fornecedores.php"    class="nav-link text-white-50 <?php echo $currentRelative=='pages/configuracoes/fornecedores.php'    ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-building me-2"></i> Fornecedores</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/configuracoes/ambientes_venda.php" class="nav-link text-white-50 <?php echo $currentRelative=='pages/configuracoes/ambientes_venda.php' ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-store me-2"></i> Canais de Venda</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/configuracoes/maquinas_venda.php"  class="nav-link text-white-50 <?php echo $currentRelative=='pages/configuracoes/maquinas_venda.php'  ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-credit-card me-2"></i> Maquininhas</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/configuracoes/usuarios.php"        class="nav-link text-white-50 <?php echo $currentRelative=='pages/configuracoes/usuarios.php'        ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-user-shield me-2"></i> Equipe / Profissionais</a></li>
                    <li class="nav-item"><a href="<?php echo BASE_URL; ?>/pages/configuracoes/estabelecimento.php" class="nav-link text-white-50 <?php echo $currentRelative=='pages/configuracoes/estabelecimento.php' ? 'text-warning fw-bold' : ''; ?>"><i class="fas fa-cog me-2"></i> Dados da Loja</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item mb-1">
            <a href="<?php echo BASE_URL; ?>/pages/analises/relatorios.php" class="nav-link text-white <?php echo $currentRelative == 'pages/analises/relatorios.php' ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="fas fa-file-alt me-2"></i> Relatórios
            </a>
        </li>

        <li class="nav-item mb-1">
            <a href="<?php echo BASE_URL; ?>/pages/ajuda/manual.php" class="nav-link text-white <?php echo $currentRelative == 'pages/ajuda/manual.php' ? 'active bg-warning text-dark fw-bold' : ''; ?>">
                <i class="fas fa-book me-2 text-info"></i> Manual &amp; Guia de Uso
            </a>
        </li>
    </ul>

    <!-- Rodapé do Menu com Botão de Sair -->
    <hr class="border-secondary">
    <div>
        <a href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-outline-danger btn-sm w-100 fw-bold">
            <i class="fas fa-sign-out-alt me-2"></i> Encerrar Sessão
        </a>
    </div>
</aside>
