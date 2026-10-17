export const tabelaBanco = $("#tabela-bancos").DataTable({
  responsive: true,
  searching: true,
  pageLength: 10,
  language: {
    url: (window.APP_BASE||"") + "/assets/js/traducaoDataTable.json",
  },
  columnDefs: [
    { width: "20%", targets: 0 },
    { width: "60%", targets: 1 },
    { width: "20%", targets: 2, orderable: false },
    { type: "string", targets: "_all" },
  ],
  columns: [
    { 
      data: "codigo",
      render: function(data) {
        return data ? data : "-";
      }
    },
    { data: "nome" },
    {
      data: null,
      render: function (data) {
        let botao = "";
        botao += `<button class='btn btn-sm btn-primary me-1 btn-editar' data-id='${data.id}'><i class='fas fa-edit'></i> Editar</button>`;
        botao += `<button class='btn btn-sm btn-danger btn-deletar' data-id='${data.id}'><i class='fas fa-trash'></i> Excluir</button>`;
        return botao;
      },
    },
  ],
});
