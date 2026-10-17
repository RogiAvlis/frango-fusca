import { tabelaMaquina } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarMaquinas() {
  $.getJSON((window.APP_BASE||"") + "/api/maquinas_venda.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-maquinas", tabelaMaquina);
    }
  });
}

export function iniciarEventos() {
  $("#btn-adicionar").on("click", function () {
    $("#form-maquina")[0].reset();
    $("#maquinaId").val("");
    $("#modal-maquina-label").text("Adicionar Maquininha");
    $("#modal-maquina").modal("show");
  });

  $("#form-maquina").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/maquinas_venda.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-maquina").modal("hide");
        carregarMaquinas();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    $.getJSON(`${window.APP_BASE||''}/api/maquinas_venda.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#maquinaId").val(res.data.id);
        $("#nome").val(res.data.nome);
        $("#tipo_taxa").val(res.data.tipo_taxa);
        $("#taxa").val(res.data.taxa);
        $("#mensalidade_valor").val(res.data.mensalidade_valor);
        $("#descricao").val(res.data.descricao);
        $("#modal-maquina-label").text("Editar Maquininha");
        $("#modal-maquina").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir esta maquininha?")) {
      $.post((window.APP_BASE||"") + "/api/maquinas_venda.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarMaquinas();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
