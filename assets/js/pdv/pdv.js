export function carregarDadosPDV() {
  $.getJSON((window.APP_BASE||"") + "/api/pdv.php?action=carregar_pdv", function (res) {
    if (res.success && res.data) {
      window.dadosPDV = res.data;
      renderizarSeletoresPDV();
      renderizarGridProdutos(res.data.produtos);
    }
  });
}

export function renderizarSeletoresPDV() {
  const data = window.dadosPDV;

  const selectCliente = $("#pdv-cliente");
  selectCliente.find("option:not(:first)").remove();
  $.each(data.clientes, function (i, c) {
    selectCliente.append(`<option value="${c.id}">${c.nome}</option>`);
  });

  const selectCanal = $("#pdv-canal");
  selectCanal.empty();
  $.each(data.canais, function (i, c) {
    selectCanal.append(`<option value="${c.id}">${c.nome}</option>`);
  });

  const selectMaquina = $("#pdv-maquina");
  selectMaquina.find("option:not(:first)").remove();
  $.each(data.maquinas, function (i, m) {
    selectMaquina.append(`<option value="${m.id}">${m.nome}</option>`);
  });

  const selectMetodo = $("#pdv-metodo");
  selectMetodo.empty();
  $.each(data.metodos, function (i, m) {
    selectMetodo.append(`<option value="${m.id}">${m.nome}</option>`);
  });
}

export function renderizarGridProdutos(produtos) {
  const grid = $("#grid-produtos-pdv");
  grid.empty();

  const termoBusca = ($("#pdv-busca-produto").val() || "").toLowerCase().trim();
  const catAtiva = $(".btn-filtro-cat.active").data("cat") || "todos";

  let produtosFiltrados = produtos || [];

  // Filtro de categoria: "todos", "acabado" (produtos próprios/bebidas), "combo"
  if (catAtiva === "combo") {
    produtosFiltrados = produtosFiltrados.filter(p => p.tipo_produto === "combo");
  } else if (catAtiva === "acabado") {
    produtosFiltrados = produtosFiltrados.filter(p => p.tipo_produto !== "combo");
  }
  // "todos" não filtra nada

  // Filtro de busca por texto
  if (termoBusca !== "") {
    produtosFiltrados = produtosFiltrados.filter(p =>
      p.nome.toLowerCase().includes(termoBusca)
    );
  }

  if (produtosFiltrados.length === 0) {
    grid.append('<div class="col-12 text-center text-muted py-5"><i class="fas fa-search fs-2 mb-2 text-secondary d-block"></i>Nenhum produto encontrado com os filtros aplicados.</div>');
    return;
  }

  $.each(produtosFiltrados, function (i, p) {
    const isCombo = p.tipo_produto === 'combo';
    const iconClass = isCombo ? 'fa-box-open text-warning' : 'fa-drumstick-bite text-primary';
    const badgeBg = isCombo ? 'bg-warning text-dark' : 'bg-success';
    const badgeLabel = isCombo ? 'Combo' : 'Produto / Bebida';

    // Montar linha de composição para combos
    let composicaoHtml = '';
    if (isCombo && p.composicao && p.composicao.length > 0) {
      composicaoHtml = `<p class="text-muted small mb-1 mt-1 px-1" style="font-size:0.72rem; line-height:1.3;">${p.composicao.join(' + ')}</p>`;
    }

    const card = `
      <div class="col-6 col-md-4 col-xl-3">
        <div class="card h-100 border-0 shadow-sm card-produto-pdv text-center p-3" data-id="${p.id}" data-nome="${p.nome}" data-preco="${p.preco_venda}" style="cursor: pointer;">
          <div class="card-body p-0 d-flex flex-column justify-content-between">
            <div>
              <i class="fas ${iconClass} fs-1 mb-2"></i>
              <h6 class="fw-bold text-dark mb-1">${p.nome}</h6>
              <span class="badge ${badgeBg} mb-1">${badgeLabel}</span>
              ${composicaoHtml}
            </div>
            <div class="mt-2">
              <h5 class="fw-800 text-primary mb-0">R$ ${parseFloat(p.preco_venda).toFixed(2)}</h5>
            </div>
          </div>
        </div>
      </div>
    `;
    grid.append(card);
  });
}

export function adicionarItemCarrinho(id, nome, preco) {
  if (!window.carrinhoPDV) window.carrinhoPDV = [];

  const itemExistente = window.carrinhoPDV.find(item => item.produto_id == id);
  if (itemExistente) {
    itemExistente.quantidade += 1;
  } else {
    window.carrinhoPDV.push({
      produto_id: id,
      nome: nome,
      quantidade: 1,
      preco_venda: parseFloat(preco)
    });
  }

  renderizarCarrinho();
}

export function renderizarCarrinho() {
  const container = $("#carrinho-itens-list");
  container.empty();

  let total = 0;
  if (!window.carrinhoPDV || window.carrinhoPDV.length === 0) {
    container.append('<div class="text-center text-muted py-4"><i class="fas fa-shopping-cart fs-2 mb-2 d-block text-secondary"></i>Carrinho vazio. Clique nos produtos ao lado para adicionar.</div>');
  } else {
    $.each(window.carrinhoPDV, function (i, item) {
      const subtotal = item.quantidade * item.preco_venda;
      total += subtotal;

      const row = `
        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
          <div style="flex: 1;">
            <strong class="d-block text-dark small">${item.nome}</strong>
            <span class="text-muted small">R$ ${item.preco_venda.toFixed(2)} un</span>
          </div>
          <div class="d-flex align-items-center me-2">
            <button class="btn btn-sm btn-outline-secondary btn-qtd-dim" data-index="${i}">-</button>
            <span class="mx-2 fw-bold">${item.quantidade}</span>
            <button class="btn btn-sm btn-outline-secondary btn-qtd-inc" data-index="${i}">+</button>
          </div>
          <div class="text-end" style="width: 80px;">
            <strong class="text-dark small">R$ ${subtotal.toFixed(2)}</strong>
            <button class="btn btn-sm text-danger p-0 ms-2 btn-del-item" data-index="${i}"><i class="fas fa-times"></i></button>
          </div>
        </div>
      `;
      container.append(row);
    });
  }

  $("#pdv-total-carrinho").text('R$ ' + total.toFixed(2));
}

export function iniciarEventosPDV() {
  carregarDadosPDV();

  // Evento de busca em tempo real
  $("#pdv-busca-produto").on("input", function () {
    if (window.dadosPDV) {
      renderizarGridProdutos(window.dadosPDV.produtos);
    }
  });

  // Evento de filtro por categoria
  $(".btn-filtro-cat").on("click", function () {
    $(".btn-filtro-cat").removeClass("active btn-primary").addClass("btn-outline-primary");
    $(this).addClass("active btn-primary").removeClass("btn-outline-primary");

    if (window.dadosPDV) {
      renderizarGridProdutos(window.dadosPDV.produtos);
    }
  });

  $(document).on("click", ".card-produto-pdv", function () {
    const id = $(this).data("id");
    const nome = $(this).data("nome");
    const preco = $(this).data("preco");
    adicionarItemCarrinho(id, nome, preco);
  });

  $(document).on("click", ".btn-qtd-dim", function () {
    const index = $(this).data("index");
    if (window.carrinhoPDV[index].quantidade > 1) {
      window.carrinhoPDV[index].quantidade -= 1;
    } else {
      window.carrinhoPDV.splice(index, 1);
    }
    renderizarCarrinho();
  });

  $(document).on("click", ".btn-qtd-inc", function () {
    const index = $(this).data("index");
    window.carrinhoPDV[index].quantidade += 1;
    renderizarCarrinho();
  });

  $(document).on("click", ".btn-del-item", function () {
    const index = $(this).data("index");
    window.carrinhoPDV.splice(index, 1);
    renderizarCarrinho();
  });

  $("#btn-limpar-carrinho").on("click", function () {
    window.carrinhoPDV = [];
    renderizarCarrinho();
  });

  $("#btn-finalizar-venda").on("click", function () {
    if (!window.carrinhoPDV || window.carrinhoPDV.length === 0) {
      alert("O carrinho está vazio.");
      return;
    }

    const payload = {
      cliente_id: $("#pdv-cliente").val(),
      ambiente_venda_id: $("#pdv-canal").val(),
      maquina_id: $("#pdv-maquina").val(),
      metodo_pagamento_id: $("#pdv-metodo").val(),
      itens: window.carrinhoPDV
    };

    $.post((window.APP_BASE||"") + "/api/pdv.php?action=registrar_venda", payload, function (res) {
      if (res.success) {
        alert("Venda realizada com sucesso e estoque atualizado!");
        window.carrinhoPDV = [];
        renderizarCarrinho();
      } else {
        alert(res.message || "Erro ao registrar venda.");
      }
    }, "json");
  });
}
