import { carregarCustos, carregarMetodosPagamentoSelect, iniciarEventosCustos } from "./listar.js";

$(document).ready(function () {
  carregarCustos();
  carregarMetodosPagamentoSelect();
  iniciarEventosCustos();
});
