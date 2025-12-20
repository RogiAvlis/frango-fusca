$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        let btn = $(this).find('button[type="submit"]');
        btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Autenticando...');
        btn.prop('disabled', true);
        
        // Aqui virá sua chamada AJAX para o src/controller/auth.php
    });
});