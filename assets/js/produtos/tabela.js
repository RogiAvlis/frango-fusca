export const tabelaProduto = $("#tabela-produtos").DataTable({
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
    { data: "nome" },
    { 
      data: "tipo_produto",
      render: function(data) {
        if (data === 'insumo') return '<span class="badge bg-secondary">Insumo / Ingrediente</span>';
        if (data === 'combo') return '<span class="badge bg-warning text-dark">Combo</span>';
        return '<span class="badge bg-success">Produto Acabado</span>';
      }
    },
    { 
      data: "preco_custo",
      render: function(data) {
        return 'R$ ' + parseFloat(data || 0).toFixed(2);
      }
    },
    { 
      data: "preco_venda",
      render: function(data) {
        return 'R$ ' + parseFloat(data || 0).toFixed(2);
      }
    },
    { 
      data: "quantidade_estoque",
      render: function(data, type, row) {
        const qtd = parseFloat(data || 0);
        const min = parseFloat(row.estoque_minimo || 0);
        const sigla = row.unidade_sigla || '';
        if (qtd <= min) {
          return `<span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-1"></i>${qtd.toFixed(3)} ${sigla}</span>`;
        }
        return `<span>${qtd.toFixed(3)} ${sigla}</span>`;
      }
    },
    {
      data: null,
      render: function (data) {
        let botao = "";
        botao += `<button class='btn btn-sm btn-primary me-1 btn-editar' data-id='${data.id}'><i class='fas fa-edit'></i> Editar / Lotes</button>`;
        botao += `<button class='btn btn-sm btn-danger btn-deletar' data-id='${data.id}'><i class='fas fa-trash'></i> Excluir</button>`;
        return botao;
      },
    },
  ],
});
