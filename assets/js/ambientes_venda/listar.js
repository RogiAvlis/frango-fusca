import { tabelaAmbiente } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarAmbientes() {
  $.getJSON((window.APP_BASE||"") + "/api/ambientes_venda.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-ambientes", tabelaAmbiente);
    }
  });
}

export function iniciarEventos() {
  $("#btn-adicionar").on("click", function () {
    $("#form-ambiente")[0].reset();
    $("#ambienteId").val("");
    $("#modal-ambiente-label").text("Adicionar Canal de Venda");
    $("#modal-ambiente").modal("show");
  });

  $("#form-ambiente").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/ambientes_venda.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-ambiente").modal("hide");
        carregarAmbientes();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    $.getJSON(`${window.APP_BASE||''}/api/ambientes_venda.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#ambienteId").val(res.data.id);
        $("#nome").val(res.data.nome);
        $("#descricao").val(res.data.descricao);
        $("#taxa").val(res.data.taxa);
        $("#modal-ambiente-label").text("Editar Canal de Venda");
        $("#modal-ambiente").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir este canal de venda?")) {
      $.post((window.APP_BASE||"") + "/api/ambientes_venda.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarAmbientes();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
