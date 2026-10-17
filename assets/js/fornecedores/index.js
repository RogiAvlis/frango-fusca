import { carregarFornecedores, iniciarEventos } from "./listar.js";

$(document).ready(function () {
  carregarFornecedores();
  iniciarEventos();
});
