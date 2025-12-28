$(document).ready(function() {
    // Manipulador para o botão de DELETAR
    $('#tabela-usuarios tbody').on('click', '.btn-delete', function () {
        var data = table.row($(this).parents('tr')).data();
        
        if (confirm('Tem certeza de que deseja inativar o usuário "' + data.nome + '"?')) {
            $.ajax({
                url: '../src/deletar/usuario.php',
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
