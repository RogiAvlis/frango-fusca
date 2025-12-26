$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Nova Unidade de Medida"
    $('button[data-bs-target="#modalUnidadeMedida"]').on('click', function() {
        $('#form-unidade-medida')[0].reset();
        $('#unidade_medida_id').val('');
        $('#modalUnidadeMedidaLabel').text('Cadastrar Unidade de Medida');
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-unidades tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        var id = data.id;

        $('#modalUnidadeMedidaLabel').text('Editar Unidade de Medida');
        
        $.ajax({
            url: '../src/consultar/unidade_medida_por_id.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                $('#unidade_medida_id').val(response.id);
                $('#sigla').val(response.sigla);
                $('#nome').val(response.nome);
                
                $('#modalUnidadeMedida').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados da unidade de medida: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-unidade-medida').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#unidade_medida_id').val();
        
        var url = id ? '../src/editar/unidade_medida.php' : '../src/cadastrar/unidade_medida.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalUnidadeMedida').modal('hide');
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
