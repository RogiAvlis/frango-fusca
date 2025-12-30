// Declara a variável da tabela no escopo global para ser acessível por outros scripts
var table;

$(document).ready(function () {
  table = $("#tabela-produtos").DataTable({
    processing: true,
    serverSide: false,
    responsive: true,
    autoWidth: false,
    language: {
      url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json",
    },
    ajax: {
      url: "../src/consultar/produto.php",
      type: "GET",
      dataSrc: "data",
    },
    columnDefs: [
      {
        targets: "_all",
        className: "text-center",
      },
    ],
    columns: [
      { data: "id" },
      {
        data: "status_registro",
        render: function (data, type, row) {
          return data == 1
            ? '<span class="badge bg-success">Ativo</span>'
            : '<span class="badge bg-danger">Inativo</span>';
        },
      },
      { data: "nome" },
      {
        data: "preco_custo",
        render: function (data, type, row) {
          return "R$ " + parseFloat(data).toFixed(2).replace(".", ",");
        },
      },
      {
        data: "preco_venda",
        render: function (data, type, row) {
          return "R$ " + parseFloat(data).toFixed(2).replace(".", ",");
        },
      },
      { data: "quantidade_comprada" },
      { data: "unidade_medida_sigla" },
      { data: "fornecedor_nome" },
      {
        data: null,
        defaultContent: `
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
  });
});
