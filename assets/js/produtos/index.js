import { carregarProdutos, carregarSelects, iniciarEventos } from "./listar.js";

$(document).ready(function () {
  carregarProdutos();
  carregarSelects();
  iniciarEventos();
});
