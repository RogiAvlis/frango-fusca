$(document).ready(function() {
    function popularSelectProdutos(selectId) {
        $.ajax({
            url: '../src/consultar/produtos_para_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var select = $(selectId);
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um produto' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar produtos:', xhr.responseText);
            }
        });
    }

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

    // Chama as funções para popular os selects quando a página carregar
    popularSelectProdutos('#produto_principal_id');
    popularSelectProdutos('#produto_ingrediente_id');
    popularSelectUnidadesMedida();

    // Exporta as funções se necessário para outros scripts
    window.popularSelectProdutos = popularSelectProdutos;
    window.popularSelectUnidadesMedida = popularSelectUnidadesMedida;
});
