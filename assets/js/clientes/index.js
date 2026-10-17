import { carregarClientes, iniciarEventos } from "./listar.js";

$(document).ready(function () {
  carregarClientes();
  iniciarEventos();
});
