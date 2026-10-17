export const tabelaFornecedor = $("#tabela-fornecedores").DataTable({
  responsive: true,
  searching: true,
  pageLength: 10,
  language: {
    url: (window.APP_BASE||"") + "/assets/js/traducaoDataTable.json",
  },
  columnDefs: [
    { width: "25%", targets: 0 },
    { width: "20%", targets: 1 },
    { width: "20%", targets: 2 },
    { width: "20%", targets: 3 },
    { width: "15%", targets: 4, orderable: false },
    { type: "string", targets: "_all" },
  ],
  columns: [
    { data: "nome" },
    { 
      data: "cnpj_cpf",
      render: function(data) {
        return data ? data : "-";
      }
    },
    { 
      data: "telefone",
      render: function(data) {
        return data ? data : "-";
      }
    },
    { 
      data: "email",
      render: function(data) {
        return data ? data : "-";
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
