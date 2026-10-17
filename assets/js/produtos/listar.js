import { tabelaProduto } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarProdutos() {
  $.getJSON((window.APP_BASE||"") + "/api/produtos.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-produtos", tabelaProduto);
    }
  });
}

export function carregarSelects() {
  $.getJSON((window.APP_BASE||"") + "/api/unidades.php?action=listar", function (res) {
    if (res && res.data) {
      const select = $("#unidade_medida_id");
      select.empty();
      $.each(res.data, function (i, item) {
        select.append(`<option value="${item.id}">${item.sigla} - ${item.nome}</option>`);
      });
    }
  });

  $.getJSON((window.APP_BASE||"") + "/api/fornecedores.php?action=listar", function (res) {
    if (res && res.data) {
      const select = $("#fornecedor_id");
      select.find("option:not(:first)").remove();
      $.each(res.data, function (i, item) {
        select.append(`<option value="${item.id}">${item.nome}</option>`);
      });
    }
  });
}

export function iniciarEventos() {
  $("#tipo_produto").on("change", function () {
    const tipo = $(this).val();
    if (tipo === "combo") {
      $("#sec-combo-items").removeClass("d-none");
    } else {
      $("#sec-combo-items").addClass("d-none");
    }
  });

  $("#btn-adicionar").on("click", function () {
    $("#form-produto")[0].reset();
    $("#produtoId").val("");
    $("#modal-produto-label").text("Adicionar Produto / Insumo");
    $("#tipo_produto").trigger("change");
    $("#container-subtabela-lotes").addClass("d-none");
    $("#tabela-lotes-body").empty();
    carregarSelects();
    $("#modal-produto").modal("show");
  });

  $("#form-produto").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/produtos.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-produto").modal("hide");
        carregarProdutos();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    carregarSelects();

    $.getJSON(`${window.APP_BASE||''}/api/produtos.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        const item = res.data;
        $("#produtoId").val(item.id);
        $("#nome").val(item.nome);
        $("#tipo_produto").val(item.tipo_produto).trigger("change");
        $("#preco_custo").val(item.preco_custo);
        $("#preco_venda").val(item.preco_venda);
        $("#estoque_minimo").val(item.estoque_minimo);
        $("#unidade_medida_id").val(item.unidade_medida_id);
        $("#fornecedor_id").val(item.fornecedor_id || "");
        $("#descricao").val(item.descricao);

        // Renderizar sub-tabela de Lotes de Estoque
        $("#container-subtabela-lotes").removeClass("d-none");
        const tbodyLotes = $("#tabela-lotes-body");
        tbodyLotes.empty();

        if (item.lotes && item.lotes.length > 0) {
          $.each(item.lotes, function (i, lote) {
            const dataFmt = new Date(lote.data_entrada).toLocaleString("pt-BR");
            tbodyLotes.append(`
              <tr>
                <td>${dataFmt}</td>
                <td><strong>${parseFloat(lote.quantidade).toFixed(3)} ${lote.unidade_sigla}</strong></td>
                <td>R$ ${parseFloat(lote.preco_custo_unitario).toFixed(2)}</td>
                <td>R$ ${parseFloat(lote.valor_total).toFixed(2)}</td>
                <td>${lote.fornecedor_nome || '-'}</td>
                <td>${lote.metodo_pagamento_nome || '-'}</td>
              </tr>
            `);
          });
        } else {
          tbodyLotes.append(`<tr><td colspan="6" class="text-center text-muted">Nenhum lote registrado para este produto.</td></tr>`);
        }

        $("#modal-produto-label").text("Editar Produto & Histórico de Lotes");
        $("#modal-produto").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir este produto?")) {
      $.post((window.APP_BASE||"") + "/api/produtos.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarProdutos();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
