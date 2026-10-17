import { carregarMovimentacoes, carregarSugestoes, carregarSelectsEstoque, iniciarEventos } from "./listar.js";

$(document).ready(function () {
  carregarMovimentacoes();
  carregarSugestoes();
  carregarSelectsEstoque();
  iniciarEventos();
});
