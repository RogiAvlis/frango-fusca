export function atualizarTabela(dados, idNomeTable, table) {
  var pageInfo = table.page.info();

  table.clear().rows.add(dados).draw();

  table.page(pageInfo.page).draw("page");

  $(document).find(".dt-button").addClass("btn btn-primary");

  criarFiltroTabela(idNomeTable, table);
}

function criarFiltroTabela(idNomeTable, table) {
  return new Promise(function (resolve, reject) {
    var thFiltro = $(`${idNomeTable} #filtro .permitir-filtro`);

    $.each(thFiltro, function (index, elemento) {
      var columnIndex = $(elemento).index();
      var columnId = $(elemento).attr("id");

      var columnValores = table
        .cells(null, columnIndex, { search: "applied" })
        .render("display")
        .toArray();

      var uniqueValores = [...new Set(columnValores)];

      $(`#datalist-${columnId} option`).remove();

      $.each(uniqueValores, function (index, value) {
        $(`#datalist-${columnId}`).append(
          `<option value='${value}'>${value}</option>`
        );
      });
    });
    resolve();
  });
}
