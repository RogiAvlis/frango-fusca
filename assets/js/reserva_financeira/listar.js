import { tabelaReserva } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarReservas() {
  $.getJSON((window.APP_BASE||"") + "/api/reserva_financeira.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-reservas", tabelaReserva);
    }
  });
}

export function iniciarEventosReserva() {
  $("#btn-adicionar").on("click", function () {
    $("#form-reserva")[0].reset();
    $("#reservaId").val("");
    $("#modal-reserva-label").text("Nova Reserva / Cofrinho");
    $("#modal-reserva").modal("show");
  });

  $("#form-reserva").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/reserva_financeira.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-reserva").modal("hide");
        carregarReservas();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-aporte", function () {
    const id = $(this).data("id");
    const nome = $(this).data("nome");
    $("#aporteReservaId").val(id);
    $("#aporte-nome-reserva").text(nome);
    $("#aporteValor").val("");
    $("#modal-aporte").modal("show");
  });

  $("#form-aporte").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/reserva_financeira.php?action=aporte", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-aporte").modal("hide");
        carregarReservas();
      } else {
        alert(res.message || "Erro ao atualizar saldo.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    $.getJSON(`${window.APP_BASE||''}/api/reserva_financeira.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#reservaId").val(res.data.id);
        $("#descricao").val(res.data.descricao);
        $("#meta_valor").val(res.data.meta_valor);
        $("#saldo_atual").val(res.data.saldo_atual);
        $("#data_previsao").val(res.data.data_previsao);
        $("#modal-reserva-label").text("Editar Reserva / Cofrinho");
        $("#modal-reserva").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir esta reserva financeira?")) {
      $.post((window.APP_BASE||"") + "/api/reserva_financeira.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarReservas();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
