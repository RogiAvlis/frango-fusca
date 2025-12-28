$(document).ready(function() {
    // Manipulador para o botão de DELETAR
    $('#tabela-receitas tbody').on('click', '.btn-delete', function () {
        var data = table.row($(this).parents('tr')).data();
        
        if (confirm('Tem certeza de que deseja inativar a receita do produto "' + data.produto_principal_nome + '" com ingrediente "' + data.produto_ingrediente_nome + '"?')) {
            $.ajax({
                url: '../src/deletar/receita.php',
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
