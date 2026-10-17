import { carregarMaquinas, iniciarEventos } from "./listar.js";

$(document).ready(function () {
  carregarMaquinas();
  iniciarEventos();
});
