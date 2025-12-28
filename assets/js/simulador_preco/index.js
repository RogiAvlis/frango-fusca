$(document).ready(function() {

    let produtosData = [];
    let ambientesData = [];
    let metodosData = [];

    function popularSelectProdutos() {
        $.ajax({
            url: '../src/consultar/produtos_para_simulacao.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                produtosData = data;
                var select = $('#simulador_produto_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um produto' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text,
                        'data-preco-custo': item.preco_custo
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar produtos:', xhr.responseText);
            }
        });
    }

    function popularSelectAmbientes() {
        $.ajax({
            url: '../src/consultar/taxas_ambiente_venda.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                ambientesData = data;
                var select = $('#simulador_ambiente_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um ambiente' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text,
                        'data-taxa': item.taxa
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar ambientes de venda:', xhr.responseText);
            }
        });
    }
    
    function popularSelectMetodos() {
        $.ajax({
            url: '../src/consultar/taxas_metodo_pagamento.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                metodosData = data;
                var select = $('#simulador_metodo_pagamento_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um método de pagamento' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text,
                        'data-taxa': item.taxa
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar métodos de pagamento:', xhr.responseText);
            }
        });
    }

    function simularPreco() {
        var formData = {
            produto_id: $('#simulador_produto_id').val(),
            preco_venda_sugerido: $('#preco_venda_sugerido').val(),
            ambiente_venda_id: $('#simulador_ambiente_id').val(),
            metodo_pagamento_id: $('#simulador_metodo_pagamento_id').val()
        };

        if (!formData.produto_id || !formData.preco_venda_sugerido) {
            // Não simula se os dados básicos não estiverem presentes
            return;
        }

        $.ajax({
            url: '../src/consultar/simular_preco.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(data) {
                $('#resultado_preco_custo').text('R$ ' + data.preco_custo.toFixed(2).replace('.', ','));
                $('#resultado_lucro_valor').text('R$ ' + data.lucro_valor.toFixed(2).replace('.', ','));
                $('#resultado_lucro_percentual').text(data.lucro_percentual.toFixed(2).replace('.', ',') + '%');
                $('#resultado_custo_final').text('R$ ' + data.custo_final.toFixed(2).replace('.', ','));
                $('#resultado_taxa_ambiente').text('R$ ' + data.valor_taxa_ambiente.toFixed(2).replace('.', ','));
                $('#resultado_taxa_metodo').text('R$ ' + data.valor_taxa_metodo_pagamento.toFixed(2).replace('.', ','));
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocorreu um erro na simulação.";
                alert(errorMessage);
            }
        });
    }

    // Event listeners
    $('#form-simulador select, #form-simulador input').on('change input', function() {
        simularPreco();
    });

    // Chama as funções para popular os selects quando a página carregar
    popularSelectProdutos();
    popularSelectAmbientes();
    popularSelectMetodos();
});
