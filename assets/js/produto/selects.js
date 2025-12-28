$(document).ready(function() {
    function popularSelectUnidadesMedida() {
        $.ajax({
            url: '../src/consultar/unidades_medida_para_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var select = $('#unidade_medida_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione uma unidade' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar unidades de medida:', xhr.responseText);
            }
        });
    }

    function popularSelectFornecedores() {
        $.ajax({
            url: '../src/consultar/fornecedores_para_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var select = $('#fornecedor_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um fornecedor' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar fornecedores:', xhr.responseText);
            }
        });
    }

    // Chama as funções para popular os selects quando a página carregar
    popularSelectUnidadesMedida();
    popularSelectFornecedores();

    // Exporta as funções se necessário para outros scripts
    window.popularSelectUnidadesMedida = popularSelectUnidadesMedida;
    window.popularSelectFornecedores = popularSelectFornecedores;
});
