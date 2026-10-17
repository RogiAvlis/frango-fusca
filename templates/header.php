<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Frango Fusca' : 'Frango Fusca - Sistema de Gestão'; ?></title>

    <!-- Estilos Vendor Locais (100% Offline) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/vendor/datatables/datatables.min.css">

    <!-- CSS Customizado -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">

    <!-- Scripts Vendor Locais carregados na head (jQuery para plugins) -->
    <script src="<?php echo BASE_URL; ?>/assets/vendor/jquery/jquery-3.7.1.min.js"></script>
    <script>window.APP_BASE = '<?php echo BASE_URL; ?>';</script>
</head>

<body class="bg-light">

<?php if (isset($_SESSION['usuario_id']) && !isset($hide_navbar)): ?>
<div class="d-flex min-vh-100">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="flex-grow-1 bg-light d-flex flex-column min-vh-100" style="overflow-x: hidden;">
<?php endif; ?>