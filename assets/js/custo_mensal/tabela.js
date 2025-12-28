// Declara a variável da tabela no escopo global para ser acessível por outros scripts
var table;

$(document).ready(function() {
    table = $('#tabela-custos').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "../src/consultar/custo_mensal.php",
            "type": "GET",
            "dataSrc": "data"
        },
        "columns": [
            { "data": "id" },
            { "data": "descricao" },
            { 
                "data": "valor",
                "render": function(data, type, row) {
                    return 'R$ ' + parseFloat(data).toFixed(2).replace('.', ',');
                }
            },
            { 
                "data": "data_pagamento",
                 "render": function(data, type, row) {
                    // Formata a data de YYYY-MM-DD para DD/MM/YYYY
                    if (type === 'display' && data) {
                        var parts = data.split('-');
                        return parts[2] + '/' + parts[1] + '/' + parts[0];
                    }
                    return data;
                }
            },
            { "data": "tipo_custo" },
            { 
                "data": "status_pagamento",
                "render": function(data, type, row) {
                    return data == 1 
                        ? '<span class="badge bg-success">Pago</span>' 
                        : '<span class="badge bg-danger">Não Pago</span>';
                }
            },
            { 
                "data": null,
                "defaultContent": `
                    <button class="btn btn-sm btn-warning btn-edit" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" title="Deletar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `,
                "orderable": false,
                "searchable": false,
                "className": "text-center"
            }
        ],
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json"
        },
        "responsive": true,
        "autoWidth": false,
        "order": [[ 3, "desc" ]] // Ordenar por data de pagamento descendente por padrão
    });
});
