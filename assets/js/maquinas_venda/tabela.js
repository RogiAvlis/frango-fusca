export const tabelaMaquina = $("#tabela-maquinas").DataTable({
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
      data: "tipo_taxa",
      render: function(data) {
        if (data === 'percentual') return 'Percentual (% por venda)';
        if (data === 'fixo_por_venda') return 'Valor Fixo (R$ por venda)';
        if (data === 'mensalidade') return 'Mensalidade Fixa';
        return data || '-';
      }
    },
    { 
      data: "taxa",
      render: function(data, type, row) {
        if (row.tipo_taxa === 'fixo_por_venda') {
          return 'R$ ' + parseFloat(data || 0).toFixed(2);
        }
        return parseFloat(data || 0).toFixed(2) + " %";
      }
    },
    { 
      data: "mensalidade_valor",
      render: function(data) {
        return 'R$ ' + parseFloat(data || 0).toFixed(2);
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
