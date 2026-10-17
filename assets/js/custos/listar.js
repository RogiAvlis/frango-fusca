import { tabelaCusto } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarCustos() {
  $.getJSON((window.APP_BASE||"") + "/api/custos.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-custos", tabelaCusto);
    }
  });
}

export function carregarMetodosPagamentoSelect() {
  $.getJSON((window.APP_BASE||"") + "/api/metodos_pagamento.php?action=listar", function (res) {
    if (res && res.data) {
      const select = $("#metodo_pagamento_id");
      select.find("option:not(:first)").remove();
      $.each(res.data, function (i, m) {
        select.append(`<option value="${m.id}">${m.nome}</option>`);
      });
    }
  });
}

export function iniciarEventosCustos() {
  $("#btn-adicionar").on("click", function () {
    $("#form-custo")[0].reset();
    $("#custoId").val("");
    $("#mes").val(new Date().getMonth() + 1);
    $("#ano").val(new Date().getFullYear());
    $("#modal-custo-label").text("Adicionar Custo / Despesa");
    carregarMetodosPagamentoSelect();
    $("#modal-custo").modal("show");
  });

  $("#form-custo").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/custos.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-custo").modal("hide");
        carregarCustos();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    carregarMetodosPagamentoSelect();
    $.getJSON(`${window.APP_BASE||''}/api/custos.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#custoId").val(res.data.id);
        $("#descricao").val(res.data.descricao);
        $("#tipo_custo").val(res.data.tipo_custo);
        $("#status_pagamento").val(res.data.status_pagamento);
        $("#valor").val(res.data.valor);
        $("#data_vencimento").val(res.data.data_vencimento);
        $("#data_pagamento").val(res.data.data_pagamento);
        $("#mes").val(res.data.mes);
        $("#ano").val(res.data.ano);
        $("#metodo_pagamento_id").val(res.data.metodo_pagamento_id || "");
        $("#modal-custo-label").text("Editar Custo / Despesa");
        $("#modal-custo").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir esta despesa?")) {
      $.post((window.APP_BASE||"") + "/api/custos.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarCustos();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
