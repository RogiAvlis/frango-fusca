// Declara a variável da tabela no escopo global para ser acessível por outros scripts
var table;

$(document).ready(function () {
  table = $("#tabela-usuarios").DataTable({
    processing: true,
    serverSide: false,
    ajax: {
      url: "../src/consultar/usuario.php",
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
      { data: "nome" },
      { data: "email" },
      {
        data: "status_registro",
        render: function (data, type, row) {
          return data == 1
            ? '<span class="badge bg-success">Ativo</span>'
            : '<span class="badge bg-danger">Inativo</span>';
        },
      },
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
    language: {
      url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json",
    },
    responsive: true,
    autoWidth: false,
  });
});
