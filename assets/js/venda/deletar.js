$(document).ready(function() {
    // Manipulador para o botão de DELETAR
    $('#tabela-vendas tbody').on('click', '.btn-delete', function () {
        var data = table.row($(this).parents('tr')).data();
        
        if (confirm('Tem certeza de que deseja deletar a venda de ID "' + data.id + '" do cliente "' + data.cliente_nome + '"?')) {
            $.ajax({
                url: '../src/deletar/venda.php',
                type: 'POST',
                data: { id: data.id },
                dataType: 'json',
                success: function(response) {
                    table.ajax.reload();
                    alert(response.message);
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocorreu um erro.";
                    alert('Erro ao deletar: ' + errorMessage);
                }
            });
        }
    });
});
