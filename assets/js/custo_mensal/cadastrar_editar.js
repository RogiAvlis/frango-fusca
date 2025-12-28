$(document).ready(function() {
    // Função para preencher automaticamente mês e ano com base na data
    $('#data_pagamento').on('change', function() {
        var date = new Date($(this).val() + 'T00:00:00'); // Adiciona T00:00:00 para evitar problemas de fuso horário
        if (!isNaN(date.getTime())) {
            $('#mes').val(date.getMonth() + 1);
            $('#ano').val(date.getFullYear());
        }
    });

    // Resetar o formulário ao clicar no botão "Novo Custo Mensal"
    $('button[data-bs-target="#modalCustoMensal"]').on('click', function() {
        $('#form-custo-mensal')[0].reset();
        $('#custo_mensal_id').val('');
        $('#modalCustoMensalLabel').text('Cadastrar Custo Mensal');

        // Preenche a data, mês e ano atuais
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var dd = String(today.getDate()).padStart(2, '0');
        
        $('#data_pagamento').val(yyyy + '-' + mm + '-' + dd);
        $('#mes').val(mm);
        $('#ano').val(yyyy);
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-custos tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalCustoMensalLabel').text('Editar Custo Mensal');
        
        $.ajax({
            url: '../src/consultar/custo_mensal_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#custo_mensal_id').val(response.id);
                $('#descricao').val(response.descricao);
                $('#valor').val(response.valor);
                $('#data_pagamento').val(response.data_pagamento);
                $('#quantidade_parcela').val(response.quantidade_parcela);
                $('#mes').val(response.mes);
                $('#ano').val(response.ano);
                $('#tipo_custo').val(response.tipo_custo);
                $('#status_pagamento').val(response.status_pagamento);
                
                $('#modalCustoMensal').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-custo-mensal').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var id = $('#custo_mensal_id').val();
        
        var url = id ? '../src/editar/custo_mensal.php' : '../src/cadastrar/custo_mensal.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                $('#modalCustoMensal').modal('hide');
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
