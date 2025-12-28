$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Novo Cliente"
    $('button[data-bs-target="#modalCliente"]').on('click', function() {
        $('#form-cliente')[0].reset();
        $('#cliente_id').val('');
        $('#modalClienteLabel').text('Cadastrar Cliente');
        $('#status_registro').val('1'); // Define o status padrão como ativo
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-clientes tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalClienteLabel').text('Editar Cliente');
        
        $.ajax({
            url: '../src/consultar/cliente_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#cliente_id').val(response.id);
                $('#status_registro').val(response.status_registro);
                $('#nome').val(response.nome);
                $('#telefone').val(response.telefone);
                
                $('#modalCliente').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-cliente').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#cliente_id').val();
        
        var url = id ? '../src/editar/cliente.php' : '../src/cadastrar/cliente.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalCliente').modal('hide');
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
