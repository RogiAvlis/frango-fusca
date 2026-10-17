import { carregarBancos, iniciarEventos } from "./listar.js";

$(document).ready(function () {
  carregarBancos();
  iniciarEventos();
});
