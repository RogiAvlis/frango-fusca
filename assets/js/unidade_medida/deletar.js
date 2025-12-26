$(document).ready(function() {
    // Manipulador para o botão de DELETAR
    $('#tabela-unidades tbody').on('click', '.btn-delete', function () {
        var data = table.row($(this).parents('tr')).data();
        var id = data.id;

        if (confirm('Tem certeza de que deseja deletar a unidade de medida "' + data.nome + '"?')) {
            $.ajax({
                url: '../src/deletar/unidade_medida.php',
                type: 'POST',
                data: { id: id },
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
