export const tabelaCusto = $("#tabela-custos").DataTable({
  responsive: true,
  searching: true,
  pageLength: 10,
  language: {
    url: (window.APP_BASE||"") + "/assets/js/traducaoDataTable.json",
  },
  columnDefs: [
    { width: "25%", targets: 0 },
    { width: "15%", targets: 1 },
    { width: "15%", targets: 2 },
    { width: "15%", targets: 3 },
    { width: "15%", targets: 4 },
    { width: "15%", targets: 5, orderable: false },
    { type: "string", targets: "_all" },
  ],
  columns: [
    { data: "descricao" },
    { 
      data: "tipo_custo",
      render: function(data) {
        if (data === 'fixo') return '<span class="badge bg-primary">Fixo (Rateável)</span>';
        return '<span class="badge bg-warning text-dark">Variável</span>';
      }
    },
    { 
      data: "valor",
      render: function(data) {
        return 'R$ ' + parseFloat(data || 0).toFixed(2);
      }
    },
    { 
      data: "status_pagamento",
      render: function(data) {
        if (data === 'pago') return '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Pago</span>';
        return '<span class="badge bg-danger"><i class="fas fa-clock me-1"></i>Pendente</span>';
      }
    },
    { 
      data: null,
      render: function(data) {
        return `${data.mes}/${data.ano}`;
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
