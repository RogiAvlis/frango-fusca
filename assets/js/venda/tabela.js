// Declara a variável da tabela no escopo global para ser acessível por outros scripts
var table;

$(document).ready(function () {
  table = $("#tabela-vendas").DataTable({
    processing: true,
    serverSide: false,
    responsive: true,
    autoWidth: false,
    language: {
      url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json",
    },
    ajax: {
      url: "../src/consultar/venda.php",
      type: "GET",
      dataSrc: "data",
    },
    columns: [
      { data: "id" },
      { data: "cliente_nome" },
      { data: "vendedor_nome" },
      { 
        data: "data_venda",
        render: function(data, type, row) {
            // Formata a data e hora de YYYY-MM-DD HH:MM:SS para DD/MM/YYYY HH:MM
            if (type === 'display' && data) {
                var parts = data.split(' ');
                var dateParts = parts[0].split('-');
                return dateParts[2] + '/' + dateParts[1] + '/' + dateParts[0] + ' ' + parts[1].substring(0, 5);
            }
            return data;
        }
      },
      { 
        data: "valor_total",
        render: function(data, type, row) {
            return 'R$ ' + parseFloat(data).toFixed(2).replace('.', ',');
        }
      },
      { data: "metodo_pagamento_nome" },
      { data: "ambiente_venda_nome" },
      {
        data: null,
        defaultContent: `
                    <button class="btn btn-sm btn-info btn-detail" title="Detalhes">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-warning btn-edit" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete" title="Deletar">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                `,
        orderable: false,
        searchable: false,
        className: "text-center",
      },
    ],
    "order": [[ 3, "desc" ]] // Ordenar por data da venda descendente por padrão
  });
});
