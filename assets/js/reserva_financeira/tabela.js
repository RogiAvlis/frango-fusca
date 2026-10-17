export const tabelaReserva = $("#tabela-reservas").DataTable({
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
    { width: "25%", targets: 3 },
    { width: "20%", targets: 4, orderable: false },
    { type: "string", targets: "_all" },
  ],
  columns: [
    { data: "descricao" },
    { 
      data: "meta_valor",
      render: function(data) {
        return 'R$ ' + parseFloat(data || 0).toFixed(2);
      }
    },
    { 
      data: "saldo_atual",
      render: function(data) {
        return '<strong class="text-success fs-6">R$ ' + parseFloat(data || 0).toFixed(2) + '</strong>';
      }
    },
    { 
      data: null,
      render: function(data) {
        const meta = parseFloat(data.meta_valor || 0);
        const atual = parseFloat(data.saldo_atual || 0);
        let pct = 0;
        if (meta > 0) {
          pct = Math.min(100, (atual / meta) * 100);
        }
        return `
          <div class="progress" style="height: 18px;">
            <div class="progress-bar bg-success font-weight-bold" role="progressbar" style="width: ${pct.toFixed(0)}%;">${pct.toFixed(0)}%</div>
          </div>
        `;
      }
    },
    {
      data: null,
      render: function (data) {
        let botao = "";
        botao += `<button class='btn btn-sm btn-success me-1 btn-aporte' data-id='${data.id}' data-nome='${data.descricao}'><i class='fas fa-piggy-bank'></i> Aporte / Resgate</button>`;
        botao += `<button class='btn btn-sm btn-primary me-1 btn-editar' data-id='${data.id}'><i class='fas fa-edit'></i> Editar Meta</button>`;
        botao += `<button class='btn btn-sm btn-danger btn-deletar' data-id='${data.id}'><i class='fas fa-trash'></i> Excluir</button>`;
        return botao;
      },
    },
  ],
});
