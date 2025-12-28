$(document).ready(function() {
    // Resetar o formulário ao clicar no botão "Nova Máquina de Venda"
    $('button[data-bs-target="#modalMaquinaVenda"]').on('click', function() {
        $('#form-maquina-venda')[0].reset();
        $('#maquina_venda_id').val('');
        $('#modalMaquinaVendaLabel').text('Cadastrar Máquina de Venda');
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-maquinas tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalMaquinaVendaLabel').text('Editar Máquina de Venda');
        
        $.ajax({
            url: '../src/consultar/maquina_venda_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#maquina_venda_id').val(response.id);
                $('#nome').val(response.nome);
                $('#descricao').val(response.descricao);
                $('#taxa').val(response.taxa);
                
                $('#modalMaquinaVenda').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-maquina-venda').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#maquina_venda_id').val();
        
        var url = id ? '../src/editar/maquina_venda.php' : '../src/cadastrar/maquina_venda.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalMaquinaVenda').modal('hide');
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
