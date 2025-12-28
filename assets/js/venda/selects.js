$(document).ready(function() {
    function popularSelectClientes() {
        $.ajax({
            url: '../src/consultar/clientes_para_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var select = $('#cliente_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um cliente' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar clientes:', xhr.responseText);
            }
        });
    }

    function popularSelectVendedores() {
        $.ajax({
            url: '../src/consultar/vendedores_para_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var select = $('#vendedor_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um vendedor' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar vendedores:', xhr.responseText);
            }
        });
    }

    function popularSelectMetodosPagamento() {
        $.ajax({
            url: '../src/consultar/metodos_pagamento_para_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var select = $('#metodo_pagamento_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um método de pagamento' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar métodos de pagamento:', xhr.responseText);
            }
        });
    }

    function popularSelectAmbientesVenda() {
        $.ajax({
            url: '../src/consultar/ambientes_venda_para_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var select = $('#ambiente_venda_id');
                select.empty();
                select.append($('<option>', { value: '', text: 'Selecione um ambiente de venda' }));
                $.each(data, function(i, item) {
                    select.append($('<option>', {
                        value: item.id,
                        text: item.text
                    }));
                });
            },
            error: function(xhr) {
                console.error('Erro ao carregar ambientes de venda:', xhr.responseText);
            }
        });
    }

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

    // Chama as funções para popular os selects quando a página carregar
    popularSelectClientes();
    popularSelectVendedores();
    popularSelectMetodosPagamento();
    popularSelectAmbientesVenda();
    popularSelectProdutos('#item_venda_produto_id');

    // Exporta as funções se necessário para outros scripts
    window.popularSelectClientes = popularSelectClientes;
    window.popularSelectVendedores = popularSelectVendedores;
    window.popularSelectMetodosPagamento = popularSelectMetodosPagamento;
    window.popularSelectAmbientesVenda = popularSelectAmbientesVenda;
    window.popularSelectProdutos = popularSelectProdutos;
});
