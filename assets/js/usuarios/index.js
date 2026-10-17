import { carregarUsuarios, carregarConvites, iniciarEventos } from "./listar.js";

$(document).ready(function () {
  carregarUsuarios();
  carregarConvites();
  iniciarEventos();
});
