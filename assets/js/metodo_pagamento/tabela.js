// Declara a variável da tabela no escopo global para ser acessível por outros scripts
var table;

$(document).ready(function () {
  table = $("#tabela-metodos").DataTable({
    processing: true,
    serverSide: false,
    responsive: true,
    autoWidth: false,
    language: {
      url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json",
    },
    ajax: {
      url: "../src/consultar/metodo_pagamento.php",
      type: "GET",
      dataSrc: "data",
    },
    columns: [
      { data: "id" },
      { data: "nome" },
      { data: "banco" },
      { data: "agencia" },
      { data: "conta" },
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
