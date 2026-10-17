<?php if (isset($_SESSION['usuario_id']) && !isset($hide_navbar)): ?>
    </div> <!-- Fechamento da div.flex-grow-1 -->
</div> <!-- Fechamento da div.d-flex min-vh-100 -->
<?php endif; ?>

<footer class="footer mt-auto py-3 bg-white border-top text-center text-muted small">
    <div class="container-fluid">
        <span>&copy; <?php echo date('Y'); ?> <strong>Frango Fusca</strong> — Sistema de Gestão Comercial Integrada</span>
    </div>
</footer>

<!-- Scripts Vendor Locais carregados no final do body (100% Offline) -->
<script src="<?php echo BASE_URL; ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/vendor/datatables/datatables.min.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/vendor/chartjs/chart.umd.js"></script>
<script src="<?php echo BASE_URL; ?>/assets/vendor/jquery-mask/jquery.mask.min.js"></script>
</body>
</html>
