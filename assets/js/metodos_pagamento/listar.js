import { tabelaMetodoPagamento } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarMetodos() {
  $.getJSON((window.APP_BASE||"") + "/api/metodos_pagamento.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-metodos", tabelaMetodoPagamento);
    }
  });
}

export function carregarBancosSelect() {
  $.getJSON((window.APP_BASE||"") + "/api/metodos_pagamento.php?action=listar_bancos", function (res) {
    if (res && res.data) {
      const select = $("#banco_id");
      select.find("option:not(:first)").remove();
      $.each(res.data, function (i, banco) {
        select.append(`<option value="${banco.id}">${banco.nome}${banco.codigo ? ' (' + banco.codigo + ')' : ''}</option>`);
      });
    }
  });
}

export function iniciarEventos() {
  $("#btn-adicionar").on("click", function () {
    $("#form-metodo")[0].reset();
    $("#metodoId").val("");
    $("#modal-metodo-label").text("Adicionar Método de Pagamento");
    carregarBancosSelect();
    $("#modal-metodo").modal("show");
  });

  $("#form-metodo").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/metodos_pagamento.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-metodo").modal("hide");
        carregarMetodos();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    carregarBancosSelect();
    $.getJSON(`${window.APP_BASE||''}/api/metodos_pagamento.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#metodoId").val(res.data.id);
        $("#nome").val(res.data.nome);
        $("#banco_id").val(res.data.banco_id || "");
        $("#agencia").val(res.data.agencia);
        $("#conta").val(res.data.conta);
        $("#modal-metodo-label").text("Editar Método de Pagamento");
        $("#modal-metodo").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir este método de pagamento?")) {
      $.post((window.APP_BASE||"") + "/api/metodos_pagamento.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarMetodos();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
