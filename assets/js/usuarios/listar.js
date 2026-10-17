import { tabelaUsuario, tabelaConvites } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarUsuarios() {
  $.getJSON((window.APP_BASE||"") + "/api/usuarios.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-usuarios", tabelaUsuario);
    }
  });
}

export function carregarConvites() {
  $.getJSON((window.APP_BASE||"") + "/api/usuarios.php?action=listar_convites", function (res) {
    if (res && res.data) {
      tabelaConvites.clear().rows.add(res.data).draw();
    }
  });
}

export function iniciarEventos() {
  $("#btn-convidar").on("click", function () {
    $("#form-convite")[0].reset();
    $("#conviteResultado").addClass("d-none");
    $("#modal-convite").modal("show");
  });

  $("#form-convite").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/usuarios.php?action=gerar_convite", $(this).serialize(), function (res) {
      if (res.success) {
        $("#resultadoCodigo").text(res.codigo);
        $("#resultadoLink").val(res.link);
        $("#conviteResultado").removeClass("d-none");
        carregarConvites();
      } else {
        alert(res.message || "Erro ao gerar convite.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-copiar", function () {
    const link = $(this).data("link") || $("#resultadoLink").val();
    navigator.clipboard.writeText(link).then(function () {
      alert("Link de convite copiado para a área de transferência!");
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja remover este profissional da loja?")) {
      $.post((window.APP_BASE||"") + "/api/usuarios.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarUsuarios();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
