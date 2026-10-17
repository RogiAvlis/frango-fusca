export function carregarDadosSimulador() {
  $.getJSON((window.APP_BASE||"") + "/api/precificacao.php", function (res) {
    if (res.success && res.data) {
      window.dadosPrecificacao = res.data;
      inicializarInterfaceSimulador();
    }
  });
}

export function inicializarInterfaceSimulador() {
  const data = window.dadosPrecificacao;
  
  // Preencher Canais
  const selectCanal = $("#sim-canal");
  selectCanal.empty();
  $.each(data.canais, function (i, c) {
    selectCanal.append(`<option value="${c.id}" data-taxa="${c.taxa}">${c.nome} (${parseFloat(c.taxa).toFixed(2)}%)</option>`);
  });

  // Preencher Maquininhas
  const selectMaq = $("#sim-maquina");
  selectMaq.empty();
  $.each(data.maquinas, function (i, m) {
    selectMaq.append(`<option value="${m.id}" data-taxa="${m.taxa}">${m.nome} (${parseFloat(m.taxa).toFixed(2)}%)</option>`);
  });

  // Preencher Custos Fixos Mensais
  $("#sim-custos-fixos").val(parseFloat(data.total_custos_fixos_mensais || 0).toFixed(2));

  // Adicionar 1ª linha de insumo simulado
  adicionarLinhaSimulada();
  calcularSimulacao();

  // Inicializar Tooltips do Bootstrap
  if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
  }
}

export function adicionarLinhaSimulada(ingId = '', qtd = 1, custoManual = null) {
  const container = $("#container-sim-insumos");
  const index = container.children().length;
  const insumos = window.dadosPrecificacao ? window.dadosPrecificacao.produtos.filter(p => p.tipo_produto === 'insumo') : [];

  let options = '<option value="">Selecione o Insumo...</option>';
  $.each(insumos, function (i, p) {
    const sel = (p.id == ingId) ? 'selected' : '';
    options += `<option value="${p.id}" data-custo="${p.preco_custo}" data-sigla="${p.unidade_sigla}" ${sel}>${p.nome} (${p.unidade_sigla}) - Custo BD: R$ ${parseFloat(p.preco_custo).toFixed(2)}</option>`;
  });

  const html = `
    <div class="row g-2 align-items-center mb-2 linha-sim-insumo" data-index="${index}">
      <div class="col-md-5">
        <select class="form-select select-sim-insumo">${options}</select>
      </div>
      <div class="col-md-3">
        <input type="number" step="0.001" class="form-control input-sim-qtd" value="${qtd}" placeholder="Quantidade">
      </div>
      <div class="col-md-3">
        <input type="number" step="0.01" class="form-control input-sim-custo" value="${custoManual !== null ? custoManual : ''}" placeholder="Custo R$ (editável)">
      </div>
      <div class="col-md-1 text-end">
        <button type="button" class="btn btn-outline-danger btn-sm btn-del-sim-linha"><i class="fas fa-times"></i></button>
      </div>
    </div>
  `;

  container.append(html);
}

export function calcularSimulacao() {
  let custoIngredientesTotal = 0;

  $(".linha-sim-insumo").each(function () {
    const select = $(this).find(".select-sim-insumo");
    const opt = select.find("option:selected");
    const qtd = parseFloat($(this).find(".input-sim-qtd").val()) || 0;
    
    let custoUnit = parseFloat($(this).find(".input-sim-custo").val());
    if (isNaN(custoUnit) || custoUnit <= 0) {
      custoUnit = parseFloat(opt.data("custo")) || 0;
      if (custoUnit > 0 && !$(this).find(".input-sim-custo").is(":focus")) {
        $(this).find(".input-sim-custo").val(custoUnit.toFixed(2));
      }
    }

    custoIngredientesTotal += (qtd * custoUnit);
  });

  const volumeMensal = parseFloat($("#sim-volume-mensal").val()) || 1;
  const custosFixosTotais = parseFloat($("#sim-custos-fixos").val()) || 0;
  const rateioCustoFixoUnitario = custosFixosTotais / volumeMensal;

  const custoTotalBaseUnitario = custoIngredientesTotal + rateioCustoFixoUnitario;

  const margemDesejadaPct = parseFloat($("#sim-margem-desejada").val()) || 0;
  const precoSugeridoBase = custoTotalBaseUnitario * (1 + (margemDesejadaPct / 100));

  const taxaCanalPct = parseFloat($("#sim-canal option:selected").data("taxa")) || 0;
  const taxaMaqPct = parseFloat($("#sim-maquina option:selected").data("taxa")) || 0;

  const somaTaxasPct = (taxaCanalPct + taxaMaqPct) / 100;
  
  let precoFinalCanal = precoSugeridoBase;
  if (somaTaxasPct < 1) {
    precoFinalCanal = precoSugeridoBase / (1 - somaTaxasPct);
  }

  const valorTaxaCanal = precoFinalCanal * (taxaCanalPct / 100);
  const valorTaxaMaq = precoFinalCanal * (taxaMaqPct / 100);
  const receitaLiquida = precoFinalCanal - valorTaxaCanal - valorTaxaMaq;
  const lucroLiquidoR = receitaLiquida - custoTotalBaseUnitario;

  // Atualizar DOM da calculadora
  $("#res-custo-insumos").text('R$ ' + custoIngredientesTotal.toFixed(2));
  $("#res-rateio-fixo").text('R$ ' + rateioCustoFixoUnitario.toFixed(2));
  $("#res-custo-total-unitario").text('R$ ' + custoTotalBaseUnitario.toFixed(2));
  $("#res-preco-sugerido-base").text('R$ ' + precoSugeridoBase.toFixed(2));
  $("#res-preco-final-canal").text('R$ ' + precoFinalCanal.toFixed(2));
  $("#res-taxas-totais").text(`- R$ ${(valorTaxaCanal + valorTaxaMaq).toFixed(2)} (${(taxaCanalPct + taxaMaqPct).toFixed(2)}%)`);
  $("#res-lucro-liquido").text('R$ ' + lucroLiquidoR.toFixed(2));
}

export function iniciarEventosSimulador() {
  carregarDadosSimulador();

  $("#btn-add-sim-insumo").on("click", function () {
    adicionarLinhaSimulada();
  });

  $(document).on("click", ".btn-del-sim-linha", function () {
    $(this).closest(".linha-sim-insumo").remove();
    calcularSimulacao();
  });

  $(document).on("change input", ".select-sim-insumo, .input-sim-qtd, .input-sim-custo, #sim-volume-mensal, #sim-custos-fixos, #sim-margem-desejada, #sim-canal, #sim-maquina", function () {
    calcularSimulacao();
  });

  $("#btn-salvar-como-receita").on("click", function () {
    const produtosAcabados = window.dadosPrecificacao ? window.dadosPrecificacao.produtos.filter(p => p.tipo_produto === 'acabado') : [];
    let options = '<option value="">Selecione o Produto Final...</option>';
    $.each(produtosAcabados, function(i, p) {
      options += `<option value="${p.id}">${p.nome}</option>`;
    });

    $("#select-salvar-produto-id").html(options);
    $("#modal-salvar-receita").modal("show");
  });

  $("#form-salvar-receita").on("submit", function (e) {
    e.preventDefault();
    const prodId = $("#select-salvar-produto-id").val();
    if (!prodId) {
      alert("Selecione o produto final para salvar a receita.");
      return;
    }

    const ingredientesPayload = [];
    $(".linha-sim-insumo").each(function () {
      const ingId = $(this).find(".select-sim-insumo").val();
      const qtd = $(this).find(".input-sim-qtd").val();
      if (ingId && qtd) {
        ingredientesPayload.push({
          produto_ingrediente_id: ingId,
          quantidade_necessaria: qtd,
          unidade_medida_id: 1
        });
      }
    });

    $.post((window.APP_BASE||"") + "/api/receitas.php?action=salvar", {
      produto_principal_id: prodId,
      ingredientes: ingredientesPayload
    }, function (res) {
      if (res.success) {
        alert(res.message);
        $("#modal-salvar-receita").modal("hide");
      } else {
        alert(res.message || "Erro ao salvar.");
      }
    }, "json");
  });
}
