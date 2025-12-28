// Declara a variável da tabela no escopo global para ser acessível por outros scripts
var itemVendaTable;

// Função para recalcular o valor total da venda
function recalcularValorTotal() {
    let valorTotal = 0;
    if (itemVendaTable && itemVendaTable.rows().count() > 0) {
        itemVendaTable.rows().every(function() {
            var data = this.data();
            valorTotal += parseFloat(data.quantidade) * parseFloat(data.preco_venda);
        });
    }
    $('#valor_total').val(valorTotal.toFixed(2));
}

$(document).ready(function() {
    itemVendaTable = $('#tabela-itens-venda').DataTable({
        processing: true,
        serverSide: false,
        responsive: true,
        autoWidth: false,
        searching: false,
        paging: false,
        info: false,
        language: {
            "emptyTable": "Nenhum item adicionado à venda"
        },
        ajax: {
            url: '../src/consultar/itens_venda_por_venda.php?venda_id=-1', // URL inicial inválida para não carregar nada
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            { data: 'id' },
            { data: 'produto_nome' },
            { data: 'quantidade' },
            { 
                data: 'preco_venda',
                render: function(data) {
                    return 'R$ ' + parseFloat(data).toFixed(2).replace('.', ',');
                }
            },
            { 
                data: null,
                render: function(data, type, row) {
                    let total = parseFloat(row.quantidade) * parseFloat(row.preco_venda);
                    return 'R$ ' + total.toFixed(2).replace('.', ',');
                }
            },
            {
                data: null,
                defaultContent: `
                    <button class="btn btn-sm btn-warning btn-edit-item" title="Editar Item">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete-item" title="Deletar Item">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `,
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        "drawCallback": function( settings ) {
            recalcularValorTotal();
        }
    });

    // Resetar o formulário de item
    function resetFormItem() {
        $('#form-item-venda')[0].reset();
        $('#item_venda_id').val('');
        $('#btn-add-item-venda').html('<i class="fas fa-plus"></i>');
    }

    // Manipulador para o envio do formulário de item de venda (Cadastro e Edição)
    $('#form-item-venda').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serializeArray();
        var id = $('#item_venda_id').val();
        var venda_id = $('#venda_id').val();
        
        // Adiciona o venda_id ao formData se não estiver presente
        var vendaIdPresente = formData.some(item => item.name === 'venda_id' && item.value);
        if (!vendaIdPresente && venda_id) {
            formData.push({name: 'venda_id', value: venda_id});
        }

        var url = id ? '../src/editar/item_venda.php' : '../src/cadastrar/item_venda.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                itemVendaTable.ajax.reload();
                resetFormItem();
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocorreu um erro.";
                alert('Erro ao salvar item: ' + errorMessage);
            }
        });
    });

    // Manipulador para o botão de EDITAR item
    $('#tabela-itens-venda tbody').on('click', '.btn-edit-item', function () {
        var data = itemVendaTable.row($(this).parents('tr')).data();
        $('#item_venda_id').val(data.id);
        $('#item_venda_produto_id').val(data.produto_id);
        $('#item_venda_quantidade').val(data.quantidade);
        $('#item_venda_preco').val(data.preco_venda);
        $('#btn-add-item-venda').html('<i class="fas fa-save"></i>');
    });

    // Manipulador para o botão de DELETAR item
    $('#tabela-itens-venda tbody').on('click', '.btn-delete-item', function () {
        var data = itemVendaTable.row($(this).parents('tr')).data();
        
        if (confirm('Tem certeza de que deseja deletar o item "' + data.produto_nome + '"?')) {
            $.ajax({
                url: '../src/deletar/item_venda.php',
                type: 'POST',
                data: { id: data.id },
                dataType: 'json',
                success: function(response) {
                    itemVendaTable.ajax.reload();
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON ? xhr.responseJSON.message : "Ocorreu um erro.";
                    alert('Erro ao deletar item: ' + errorMessage);
                }
            });
        }
    });

    // Exporta a tabela para ser acessível por outros scripts
    window.itemVendaTable = itemVendaTable;
});
