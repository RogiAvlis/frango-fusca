import { carregarMetodos, carregarBancosSelect, iniciarEventos } from "./listar.js";

$(document).ready(function () {
  carregarMetodos();
  carregarBancosSelect();
  iniciarEventos();
});
