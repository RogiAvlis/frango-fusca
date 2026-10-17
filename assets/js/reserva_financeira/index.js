import { carregarReservas, iniciarEventosReserva } from "./listar.js";

$(document).ready(function () {
  carregarReservas();
  iniciarEventosReserva();
});
