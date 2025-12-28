$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Novo Ambiente de Venda"
    $('button[data-bs-target="#modalAmbienteVenda"]').on('click', function() {
        $('#form-ambiente-venda')[0].reset();
        $('#ambiente_venda_id').val('');
        $('#modalAmbienteVendaLabel').text('Cadastrar Ambiente de Venda');
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-ambientes tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalAmbienteVendaLabel').text('Editar Ambiente de Venda');
        
        $.ajax({
            url: '../src/consultar/ambiente_venda_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#ambiente_venda_id').val(response.id);
                $('#nome').val(response.nome);
                $('#descricao').val(response.descricao);
                $('#taxa').val(response.taxa);
                
                $('#modalAmbienteVenda').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-ambiente-venda').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#ambiente_venda_id').val();
        
        var url = id ? '../src/editar/ambiente_venda.php' : '../src/cadastrar/ambiente_venda.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalAmbienteVenda').modal('hide');
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
