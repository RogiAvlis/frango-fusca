$(document).ready(function() {
    // Manipulador para o botão de DELETAR
    $('#tabela-maquinas tbody').on('click', '.btn-delete', function () {
        var data = table.row($(this).parents('tr')).data();
        
        if (confirm('Tem certeza de que deseja deletar a máquina de venda "' + data.nome + '"?')) {
            $.ajax({
                url: '../src/deletar/maquina_venda.php',
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
