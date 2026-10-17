import { tabelaEstoque, tabelaSugestao } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarMovimentacoes() {
  $.getJSON((window.APP_BASE||"") + "/api/estoque.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-estoque", tabelaEstoque);
    }
  });
}

export function carregarSugestoes() {
  $.getJSON((window.APP_BASE||"") + "/api/estoque.php?action=sugestoes_compra", function (res) {
    if (res && res.data) {
      tabelaSugestao.clear().rows.add(res.data).draw();
      if (res.data.length > 0) {
        $("#alerta-sugestao-count").text(res.data.length).removeClass("d-none");
      } else {
        $("#alerta-sugestao-count").addClass("d-none");
      }
    }
  });
}

export function carregarSelectsEstoque() {
  $.getJSON((window.APP_BASE||"") + "/api/produtos.php?action=listar", function (res) {
    if (res && res.data) {
      const select = $("#produto_id");
      select.find("option:not(:first)").remove();
      $.each(res.data, function (i, p) {
        select.append(`<option value="${p.id}" data-unidade="${p.unidade_medida_id}" data-sigla="${p.unidade_sigla}" data-custo="${p.preco_custo}">${p.nome} (${p.unidade_sigla}) - Custo Atual: R$ ${parseFloat(p.preco_custo).toFixed(2)}</option>`);
      });
    }
  });

  $.getJSON((window.APP_BASE||"") + "/api/fornecedores.php?action=listar", function (res) {
    if (res && res.data) {
      const select = $("#fornecedor_id");
      select.find("option:not(:first)").remove();
      $.each(res.data, function (i, f) {
        select.append(`<option value="${f.id}">${f.nome}</option>`);
      });
    }
  });

  $.getJSON((window.APP_BASE||"") + "/api/metodos_pagamento.php?action=listar", function (res) {
    if (res && res.data) {
      const select = $("#metodo_pagamento_id");
      select.find("option:not(:first)").remove();
      $.each(res.data, function (i, m) {
        select.append(`<option value="${m.id}">${m.nome}${m.banco_nome ? ' (' + m.banco_nome + ')' : ''}</option>`);
      });
    }
  });
}

export function iniciarEventos() {
  $("#produto_id").on("change", function () {
    const opt = $(this).find("option:selected");
    const umId = opt.data("unidade");
    const custo = opt.data("custo");
    if (umId) {
      $("#unidade_medida_id").val(umId);
    }
    if (custo) {
      $("#preco_custo_unitario").val(custo);
    }
  });

  $("#btn-adicionar").on("click", function () {
    $("#form-estoque")[0].reset();
    carregarSelectsEstoque();
    $("#modal-estoque").modal("show");
  });

  $("#form-estoque").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/estoque.php?action=registrar_entrada", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-estoque").modal("hide");
        carregarMovimentacoes();
        carregarSugestoes();
      } else {
        alert(res.message || "Erro ao registrar entrada.");
      }
    }, "json");
  });
}
