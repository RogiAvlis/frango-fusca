// Declara a variável da tabela no escopo global para ser acessível por outros scripts
var table;

$(document).ready(function () {
  table = $("#tabela-receitas").DataTable({
    processing: true,
    serverSide: false,
    responsive: true,
    autoWidth: false,
    language: {
      url: "https://cdn.datatables.net/plug-ins/2.0.8/i18n/pt-BR.json",
    },
    ajax: {
      url: "../src/consultar/receita.php",
      type: "GET",
      dataSrc: "data",
    },
    columns: [
      { data: "id" },
      { 
        data: "status_registro",
        render: function(data, type, row) {
            return data == 1 
                ? '<span class="badge bg-success">Ativo</span>' 
                : '<span class="badge bg-danger">Inativo</span>';
        }
      },
      { data: "produto_principal_nome" },
      { data: "produto_ingrediente_nome" },
      { data: "quantidade_necessaria" },
      { data: "unidade_medida_sigla" },
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
