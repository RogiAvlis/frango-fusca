import { tabelaFornecedor } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarFornecedores() {
  $.getJSON((window.APP_BASE||"") + "/api/fornecedores.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-fornecedores", tabelaFornecedor);
    }
  });
}

export function iniciarEventos() {
  $("#btn-adicionar").on("click", function () {
    $("#form-fornecedor")[0].reset();
    $("#fornecedorId").val("");
    $("#modal-fornecedor-label").text("Adicionar Fornecedor");
    $("#modal-fornecedor").modal("show");
  });

  $("#form-fornecedor").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/fornecedores.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-fornecedor").modal("hide");
        carregarFornecedores();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    $.getJSON(`${window.APP_BASE||''}/api/fornecedores.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#fornecedorId").val(res.data.id);
        $("#nome").val(res.data.nome);
        $("#cnpj_cpf").val(res.data.cnpj_cpf);
        $("#telefone").val(res.data.telefone);
        $("#email").val(res.data.email);
        $("#endereco").val(res.data.endereco);
        $("#modal-fornecedor-label").text("Editar Fornecedor");
        $("#modal-fornecedor").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir este fornecedor?")) {
      $.post((window.APP_BASE||"") + "/api/fornecedores.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarFornecedores();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
