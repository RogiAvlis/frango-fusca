$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Novo Método de Pagamento"
    $('button[data-bs-target="#modalMetodoPagamento"]').on('click', function() {
        $('#form-metodo-pagamento')[0].reset();
        $('#metodo_pagamento_id').val('');
        $('#modalMetodoPagamentoLabel').text('Cadastrar Método de Pagamento');
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-metodos tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalMetodoPagamentoLabel').text('Editar Método de Pagamento');
        
        $.ajax({
            url: '../src/consultar/metodo_pagamento_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#metodo_pagamento_id').val(response.id);
                $('#nome').val(response.nome);
                $('#banco').val(response.banco);
                $('#agencia').val(response.agencia);
                $('#conta').val(response.conta);
                
                $('#modalMetodoPagamento').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-metodo-pagamento').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#metodo_pagamento_id').val();
        
        var url = id ? '../src/editar/metodo_pagamento.php' : '../src/cadastrar/metodo_pagamento.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalMetodoPagamento').modal('hide');
                table.ajax.reload();
                alert(response.message);
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocorreu um erro.";
                alert('Erro ao salvar: ' + errorMessage);
            }
        });
    });
});
