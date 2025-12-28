$(document).ready(function() {
    // Função para obter a data e hora atual no formato YYYY-MM-DDTHH:MM
    function getCurrentDateTimeLocal() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    // Resetar o formulário ao clicar no botão "Nova Venda"
    $('button[data-bs-target="#modalVenda"]').on('click', function() {
        $('#form-venda')[0].reset();
        $('#venda_id').val('');
        $('#modalVendaLabel').text('Cadastrar Venda');
        $('#data_venda').val(getCurrentDateTimeLocal()); // Preenche com data e hora atual
        $('#valor_total').val('0.00'); // Valor inicial
        
        // Repopular selects
        if (window.popularSelectClientes) window.popularSelectClientes();
        if (window.popularSelectVendedores) window.popularSelectVendedores();
        if (window.popularSelectMetodosPagamento) window.popularSelectMetodosPagamento();
        if (window.popularSelectAmbientesVenda) window.popularSelectAmbientesVenda();
        if (window.popularSelectProdutos) window.popularSelectProdutos('#item_venda_produto_id');

        // Limpar formulário de item de venda e tabela
        $('#form-item-venda')[0].reset();
        $('#item_venda_id').val('');
        $('#item_venda_venda_id').val('');
        if (window.itemVendaTable) {
            window.itemVendaTable.clear().draw();
        }
    });

    // Manipulador para o botão de EDITAR
    $('#tabela-vendas tbody').on('click', '.btn-edit', function () {
        var data = table.row($(this).parents('tr')).data();
        
        $('#modalVendaLabel').text('Editar Venda');
        
        $.ajax({
            url: '../src/consultar/venda_por_id.php',
            type: 'GET',
            data: { id: data.id },
            dataType: 'json',
            success: function(response) {
                $('#venda_id').val(response.id);
                $('#valor_total').val(response.valor_total);

                // Formata a data de YYYY-MM-DD HH:MM:SS para YYYY-MM-DDTHH:MM para o input datetime-local
                if (response.data_venda) {
                    $('#data_venda').val(response.data_venda.substring(0, 16));
                }

                // Seleciona os valores corretos nos selects
                if (window.popularSelectClientes) {
                    window.popularSelectClientes();
                    $('#cliente_id').val(response.cliente_id);
                }
                if (window.popularSelectVendedores) {
                    window.popularSelectVendedores();
                    $('#vendedor_id').val(response.vendedor_id);
                }
                if (window.popularSelectMetodosPagamento) {
                    window.popularSelectMetodosPagamento();
                    $('#metodo_pagamento_id').val(response.metodo_pagamento_id);
                }
                if (window.popularSelectAmbientesVenda) {
                    window.popularSelectAmbientesVenda();
                    $('#ambiente_venda_id').val(response.ambiente_venda_id);
                }

                // Carrega os itens de venda
                if (window.itemVendaTable) {
                    window.itemVendaTable.ajax.url('../src/consultar/itens_venda_por_venda.php?venda_id=' + response.id).load();
                }
                $('#item_venda_venda_id').val(response.id);
                
                $('#modalVenda').modal('show');
            },
            error: function(xhr) {
                alert('Erro ao buscar dados: ' + (xhr.responseJSON ? xhr.responseJSON.message : "Erro desconhecido."));
            }
        });
    });

    // Manipulador para o envio do formulário (Cadastro e Edição)
    $('#form-venda').on('submit', function(e) {
        e.preventDefault();

        var formDataArray = $(this).serializeArray();
        var formData = {};
        $(formDataArray).each(function(index, obj){
            formData[obj.name] = obj.value;
        });

        // Formata a data e hora para o backend
        if (formData['data_venda']) {
            formData['data_venda'] = formData['data_venda'].replace('T', ' ') + ':00'; // Adiciona segundos
        }
        
        var id = $('#venda_id').val();
        var url = id ? '../src/editar/venda.php' : '../src/cadastrar/venda.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData, // Envia como objeto para formatação de data
            dataType: 'json',
            success: function(response) {
                $('#modalVenda').modal('hide');
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
