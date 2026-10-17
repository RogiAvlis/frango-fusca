export function carregarProdutosPrincipais() {
  $.getJSON((window.APP_BASE||"") + "/api/produtos.php?action=listar", function (res) {
    if (res && res.data) {
      const select = $("#select-produto-principal");
      select.find("option:not(:first)").remove();
      $.each(res.data, function (i, p) {
        if (p.tipo_produto === 'acabado') {
          select.append(`<option value="${p.id}">${p.nome}</option>`);
        }
      });
    }
  });
}

export function carregarListaIngredientesDisponiveis(callback) {
  $.getJSON((window.APP_BASE||"") + "/api/produtos.php?action=listar", function (res) {
    if (res && res.data) {
      window.listaInsumosGlobal = res.data.filter(p => p.tipo_produto === 'insumo');
      if (callback) callback();
    }
  });
}

export function adicionarLinhaIngrediente(ingId = '', qtd = '', umId = '') {
  const container = $("#container-linhas-ingredientes");
  const index = container.children().length;
  
  let optionsInsumos = '<option value="">Selecione o Insumo...</option>';
  if (window.listaInsumosGlobal) {
    $.each(window.listaInsumosGlobal, function (i, item) {
      const sel = (item.id == ingId) ? 'selected' : '';
      optionsInsumos += `<option value="${item.id}" data-sigla="${item.unidade_sigla}" data-custo="${item.preco_custo}" ${sel}>${item.nome} (${item.unidade_sigla}) - Custo: R$ ${parseFloat(item.preco_custo).toFixed(2)}</option>`;
    });
  }

  const html = `
    <div class="row g-2 align-items-center mb-2 linha-ingrediente" data-index="${index}">
      <div class="col-md-6">
        <select name="ingredientes[${index}][produto_ingrediente_id]" class="form-select select-insumo-item" required>
          ${optionsInsumos}
        </select>
      </div>
      <div class="col-md-4">
        <div class="input-group">
          <input type="number" step="0.001" name="ingredientes[${index}][quantidade_necessaria]" class="form-control input-qtd-item" value="${qtd}" placeholder="Quantidade" required>
          <span class="input-group-text sigla-unidade-item">Qtd</span>
        </div>
      </div>
      <div class="col-md-2 text-end">
        <button type="button" class="btn btn-outline-danger btn-remover-linha"><i class="fas fa-trash"></i></button>
      </div>
    </div>
  `;

  container.append(html);
}

export function carregarReceitaDoProduto(produtoId) {
  if (!produtoId) {
    $("#container-linhas-ingredientes").empty();
    return;
  }

  $.getJSON(`${window.APP_BASE||''}/api/receitas.php?action=buscar&produto_id=${produtoId}`, function (res) {
    const container = $("#container-linhas-ingredientes");
    container.empty();

    if (res && res.data && res.data.length > 0) {
      $.each(res.data, function (i, item) {
        adicionarLinhaIngrediente(item.produto_ingrediente_id, item.quantidade_necessaria, item.unidade_medida_id);
      });
    } else {
      adicionarLinhaIngrediente();
    }
  });
}

export function iniciarEventosReceita() {
  carregarListaIngredientesDisponiveis();
  carregarProdutosPrincipais();

  $("#select-produto-principal").on("change", function () {
    const id = $(this).val();
    carregarReceitaDoProduto(id);
  });

  $("#btn-add-ingrediente-linha").on("click", function () {
    adicionarLinhaIngrediente();
  });

  $(document).on("click", ".btn-remover-linha", function () {
    $(this).closest(".linha-ingrediente").remove();
  });

  $("#form-receita").on("submit", function (e) {
    e.preventDefault();
    $.post((window.APP_BASE||"") + "/api/receitas.php?action=salvar", $(this).serialize(), function (res) {
      if (res.success) {
        alert(res.message);
      } else {
        alert(res.message || "Erro ao salvar ficha técnica.");
      }
    }, "json");
  });
}
