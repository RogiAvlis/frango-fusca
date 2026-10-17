export const tabelaUsuario = $("#tabela-usuarios").DataTable({
  responsive: true,
  searching: true,
  pageLength: 10,
  language: {
    url: (window.APP_BASE||"") + "/assets/js/traducaoDataTable.json",
  },
  columnDefs: [
    { width: "35%", targets: 0 },
    { width: "35%", targets: 1 },
    { width: "15%", targets: 2 },
    { width: "15%", targets: 3, orderable: false },
    { type: "string", targets: "_all" },
  ],
  columns: [
    { data: "nome" },
    { data: "email" },
    { 
      data: "perfil",
      render: function(data) {
        if (data === 'admin') return '<span class="badge bg-danger">Administrador</span>';
        if (data === 'gerente') return '<span class="badge bg-primary">Gerente</span>';
        if (data === 'caixa') return '<span class="badge bg-info">Caixa</span>';
        return '<span class="badge bg-secondary">Vendedor</span>';
      }
    },
    {
      data: null,
      render: function (data) {
        let botao = "";
        botao += `<button class='btn btn-sm btn-danger btn-deletar' data-id='${data.id}'><i class='fas fa-trash'></i> Excluir</button>`;
        return botao;
      },
    },
  ],
});

export const tabelaConvites = $("#tabela-convites").DataTable({
  responsive: true,
  searching: false,
  paging: false,
  info: false,
  language: {
    url: (window.APP_BASE||"") + "/assets/js/traducaoDataTable.json",
  },
  columns: [
    { data: "email" },
    { 
      data: "codigo_convite",
      render: function(data) {
        return `<strong class="text-primary">${data}</strong>`;
      }
    },
    { data: "perfil" },
    {
      data: null,
      render: function(data) {
        const link = window.location.origin + (window.APP_BASE || '') + '/aceitar_convite.php?codigo=' + data.codigo_convite;
        return `<button class="btn btn-sm btn-outline-secondary btn-copiar" data-link="${link}"><i class="fas fa-copy me-1"></i> Copiar Link</button>`;
      }
    }
  ]
});
