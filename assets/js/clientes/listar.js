import { tabelaCliente } from "./tabela.js";
import { atualizarTabela } from "../atualizarTabela.js";

export function carregarClientes() {
  $.getJSON((window.APP_BASE||"") + "/api/clientes.php?action=listar", function (res) {
    if (res && res.data) {
      atualizarTabela(res.data, "#tabela-clientes", tabelaCliente);
    }
  });
}

export function iniciarEventos() {
  $("#btn-adicionar").on("click", function () {
    $("#form-cliente")[0].reset();
    $("#clienteId").val("");
    $("#modal-cliente-label").text("Adicionar Cliente");
    $("#modal-cliente").modal("show");
  });

  $("#form-cliente").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/clientes.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        $("#modal-cliente").modal("hide");
        carregarClientes();
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });

  $(document).on("click", ".btn-editar", function () {
    const id = $(this).data("id");
    $.getJSON(`${window.APP_BASE||''}/api/clientes.php?action=buscar&id=${id}`, function (res) {
      if (res.success && res.data) {
        $("#clienteId").val(res.data.id);
        $("#nome").val(res.data.nome);
        $("#telefone").val(res.data.telefone);
        $("#email").val(res.data.email);
        $("#endereco").val(res.data.endereco);
        $("#modal-cliente-label").text("Editar Cliente");
        $("#modal-cliente").modal("show");
      }
    });
  });

  $(document).on("click", ".btn-deletar", function () {
    const id = $(this).data("id");
    if (confirm("Tem certeza que deseja excluir este cliente?")) {
      $.post((window.APP_BASE||"") + "/api/clientes.php?action=deletar", { id: id }, function (res) {
        if (res.success) {
          carregarClientes();
        } else {
          alert(res.message || "Erro ao excluir.");
        }
      }, "json");
    }
  });
}
