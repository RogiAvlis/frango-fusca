import { tabelaUnidade } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarUnidades() {
  $.getJSON((window.APP_BASE||"") + "/api/unidades.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-unidades", tabelaUnidade);
    }
  });
}

export function iniciarEventos() {
  $("#btn-adicionar").on("click", function () {
    $("#form-unidade")[0].reset();
    $("#unidadeId").val("");
    $("#modal-unidade-label").text("Adicionar Unidade de Medida");
    $("#modal-unidade").modal("show");
  });

  $("#form-unidade").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/unidades.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-unidade").modal("hide");
        carregarUnidades();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    $.getJSON(`${window.APP_BASE||''}/api/unidades.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#unidadeId").val(res.data.id);
        $("#sigla").val(res.data.sigla);
        $("#nome").val(res.data.nome);
        $("#modal-unidade-label").text("Editar Unidade de Medida");
        $("#modal-unidade").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir esta unidade de medida?")) {
      $.post((window.APP_BASE||"") + "/api/unidades.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarUnidades();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
