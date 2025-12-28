$(document).ready(function() {
    // Manipulador para o botão de DELETAR
    $('#tabela-clientes tbody').on('click', '.btn-delete', function () {
        var data = table.row($(this).parents('tr')).data();
        
        if (confirm('Tem certeza de que deseja inativar o cliente "' + data.nome + '"?')) {
            $.ajax({
                url: '../src/deletar/cliente.php',
                type: 'POST',
                data: { id: data.id },
                dataType: 'json',
                success: function(response) {
                    table.ajax.reload();
                    alert(response.message);
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocorreu um erro.";
                    alert('Erro ao inativar: ' + errorMessage);
                }
            });
        }
    });
});
