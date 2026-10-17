export const tabelaEstoque = $("#tabela-estoque").DataTable({
  responsive: true,
  searching: true,
  pageLength: 10,
  language: {
    url: (window.APP_BASE||"") + "/assets/js/traducaoDataTable.json",
  },
  columnDefs: [
    { width: "15%", targets: 0 },
    { width: "25%", targets: 1 },
    { width: "15%", targets: 2 },
    { width: "15%", targets: 3 },
    { width: "15%", targets: 4 },
    { width: "15%", targets: 5 },
    { type: "string", targets: "_all" },
  ],
  columns: [
    { 
      data: "data_entrada",
      render: function(data) {
        return new Date(data).toLocaleString("pt-BR");
      }
    },
    { data: "produto_nome" },
    { 
      data: "quantidade",
      render: function(data, type, row) {
        return parseFloat(data || 0).toFixed(3) + ' ' + (row.unidade_sigla || '');
      }
    },
    { 
      data: "preco_custo_unitario",
      render: function(data) {
        return 'R$ ' + parseFloat(data || 0).toFixed(2);
      }
    },
    { 
      data: "valor_total",
      render: function(data) {
        return '<strong class="text-dark">R$ ' + parseFloat(data || 0).toFixed(2) + '</strong>';
      }
    },
    { 
      data: "metodo_pagamento_nome",
      render: function(data) {
        return data ? data : "-";
      }
    }
  ],
});

export const tabelaSugestao = $("#tabela-sugestao").DataTable({
  responsive: true,
  searching: false,
  paging: false,
  info: false,
  language: {
    url: (window.APP_BASE||"") + "/assets/js/traducaoDataTable.json",
  },
  columns: [
    { data: "nome" },
    { 
      data: "quantidade_estoque",
      render: function(data, type, row) {
        return `<span class="text-danger fw-bold">${parseFloat(data || 0).toFixed(3)} ${row.unidade_sigla}</span>`;
      }
    },
    { 
      data: "estoque_minimo",
      render: function(data, type, row) {
        return `${parseFloat(data || 0).toFixed(3)} ${row.unidade_sigla}`;
      }
    },
    { 
      data: "quantidade_sugerida",
      render: function(data, type, row) {
        return `<span class="badge bg-warning text-dark fs-6">+ ${parseFloat(data || 0).toFixed(3)} ${row.unidade_sigla}</span>`;
      }
    },
    { data: "fornecedor_nome", render: function(d) { return d || '-'; } }
  ]
});
