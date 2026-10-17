import { tabelaBanco } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarBancos() {
  $.getJSON((window.APP_BASE||"") + "/api/bancos.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-bancos", tabelaBanco);
    }
  });
}

export function iniciarEventos() {
  $("#btn-adicionar").on("click", function () {
    $("#form-banco")[0].reset();
    $("#bancoId").val("");
    $("#modal-banco-label").text("Adicionar Banco");
    $("#modal-banco").modal("show");
  });

  $("#form-banco").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/bancos.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-banco").modal("hide");
        carregarBancos();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    $.getJSON(`${window.APP_BASE||''}/api/bancos.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#bancoId").val(res.data.id);
        $("#codigo").val(res.data.codigo);
        $("#nome").val(res.data.nome);
        $("#modal-banco-label").text("Editar Banco");
        $("#modal-banco").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir este banco?")) {
      $.post((window.APP_BASE||"") + "/api/bancos.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarBancos();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
