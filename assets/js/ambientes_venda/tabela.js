export const tabelaAmbiente = $("#tabela-ambientes").DataTable({
  responsive: true,
  searching: true,
  pageLength: 10,
  language: {
    url: (window.APP_BASE||"") + "/assets/js/traducaoDataTable.json",
  },
  columnDefs: [
    { width: "30%", targets: 0 },
    { width: "35%", targets: 1 },
    { width: "20%", targets: 2 },
    { width: "15%", targets: 3, orderable: false },
    { type: "string", targets: "_all" },
  ],
  columns: [
    { data: "nome" },
    { 
      data: "descricao",
      render: function(data) {
        return data ? data : "-";
      }
    },
    { 
      data: "taxa",
      render: function(data) {
        return parseFloat(data || 0).toFixed(2) + " %";
      }
    },
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
