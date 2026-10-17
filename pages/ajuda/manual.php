<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Manual & Guia Prático do Usuário';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-dark mb-0 h2"><i class="fas fa-book-reader text-info me-2"></i> Manual & Guia Prático do Usuário</h1>
            <p class="text-muted small mb-0">Guia completo, com linguagem simples e exemplos reais, para você operar o sistema com total confiança</p>
        </div>
        <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-outline-primary btn-sm fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Voltar ao Painel
        </a>
    </div>

    <!-- Navegação em Abas do Manual -->
    <ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 shadow-sm border flex-wrap gap-1" id="manualTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active fw-bold" id="tab-inicio-tab" data-bs-toggle="pill" data-bs-target="#tab-inicio" type="button"><i class="fas fa-compass me-1"></i> Primeiros Passos</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-pdv-tab" data-bs-toggle="pill" data-bs-target="#tab-pdv" type="button"><i class="fas fa-cash-register me-1"></i> Registrar Vendas</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-precificacao-tab" data-bs-toggle="pill" data-bs-target="#tab-precificacao" type="button"><i class="fas fa-calculator me-1"></i> Precificação</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-estoque-tab" data-bs-toggle="pill" data-bs-target="#tab-estoque" type="button"><i class="fas fa-boxes me-1"></i> Estoque & Compras</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-financeiro-tab" data-bs-toggle="pill" data-bs-target="#tab-financeiro" type="button"><i class="fas fa-wallet me-1"></i> Financeiro</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-analises-tab" data-bs-toggle="pill" data-bs-target="#tab-analises" type="button"><i class="fas fa-chart-line me-1"></i> Análises & Painéis</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-cadastros-tab" data-bs-toggle="pill" data-bs-target="#tab-cadastros" type="button"><i class="fas fa-cogs me-1"></i> Cadastros & Loja</button>
        </li>
        <li class="nav-item">
            <button class="nav-link fw-bold" id="tab-faq-tab" data-bs-toggle="pill" data-bs-target="#tab-faq" type="button"><i class="fas fa-question-circle me-1"></i> Dúvidas Frequentes</button>
        </li>
    </ul>

    <!-- Conteúdo do Manual -->
    <div class="tab-content" id="manualTabsContent">

        <!-- ================================================================ -->
        <!-- ABA 1: PRIMEIROS PASSOS -->
        <!-- ================================================================ -->
        <div class="tab-pane fade show active" id="tab-inicio" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-800 text-dark mb-3"><i class="fas fa-smile text-warning me-2"></i> Seja Bem-Vindo ao Sistema Frango Fusca!</h4>
                <p class="lead text-muted">
                    Este sistema foi criado especialmente para o dia a dia do seu estabelecimento. Aqui você controla vendas, estoque, custos, precificação e muito mais — tudo em um só lugar, sem complicações.
                </p>
                <hr class="my-4">

                <h5 class="fw-bold text-dark mb-3">📍 Mapa do Sistema — O Que Cada Menu Faz:</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-warning mb-2"><i class="fas fa-chart-pie me-2"></i> Painel do Dia</h6>
                            <p class="small text-muted mb-0">Sua tela inicial. Mostra o faturamento de hoje, quantidade de pedidos e compara com a mesma semana passada. É o resumo rápido do seu dia.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-success mb-2"><i class="fas fa-cash-register me-2"></i> Ponto de Venda</h6>
                            <p class="small text-muted mb-0">Onde você registra as vendas do dia. Adiciona produtos ao carrinho, escolhe o meio de pagamento e finaliza. O estoque é baixado automaticamente.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-warning mb-2"><i class="fas fa-calculator me-2"></i> Precificação Inteligente</h6>
                            <p class="small text-muted mb-0">Calcula o preço correto de venda levando em conta ingredientes, despesas fixas, lucro desejado e taxas do iFood ou maquininha.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-info mb-2"><i class="fas fa-boxes me-2"></i> Estoque & Produtos</h6>
                            <p class="small text-muted mb-0">Cadastre ingredientes, produtos e combos. Registre compras de fornecedores para manter o estoque em dia e evitar surpresas.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-primary mb-2"><i class="fas fa-chart-line me-2"></i> Análises & Decisões</h6>
                            <p class="small text-muted mb-0">Painel avançado com gráficos de evolução de vendas, produtos mais vendidos, impacto de taxas e muito mais. Para decisões estratégicas.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-danger mb-2"><i class="fas fa-wallet me-2"></i> Financeiro</h6>
                            <p class="small text-muted mb-0">Controle suas despesas mensais (aluguel, luz, gás) e crie cofrinhos virtuais para guardar dinheiro para emergências ou metas.</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="fw-bold text-dark mb-3">🔑 Como Fazer Login no Sistema:</h5>
                <ol class="text-muted small">
                    <li class="mb-2">Abra o navegador e acesse o endereço do sistema (ex: <code>http://localhost/frango-fusca</code>).</li>
                    <li class="mb-2">Na tela de login, digite seu <strong>e-mail</strong> e sua <strong>senha</strong>.</li>
                    <li class="mb-2">Clique em <strong>"Entrar"</strong>. Se os dados estiverem corretos, você será levado direto ao Painel do Dia.</li>
                    <li>Se esqueceu a senha, peça ao administrador do sistema para redefini-la na tela de <strong>Equipe / Profissionais</strong>.</li>
                </ol>

                <hr class="my-4">
                <h5 class="fw-bold text-dark mb-3">🗺️ Como Navegar pelo Sistema:</h5>
                <p class="text-muted small">O menu lateral (à esquerda) é o seu mapa de navegação. Ele está sempre visível. Clique em qualquer item para ir à tela correspondente. Menus com seta <i class="fas fa-chevron-down small"></i> são expandíveis e mostram submenus.</p>
                <div class="alert alert-info border-0 rounded-3">
                    <strong><i class="fas fa-lightbulb me-2"></i> Dica:</strong> Comece sempre pelo <strong>Ponto de Venda</strong> para registrar os pedidos do dia. No final do dia, confira o <strong>Painel do Dia</strong> para ver como foi.
                </div>
            </div>
        </div>

        <!-- ================================================================ -->
        <!-- ABA 2: REGISTRAR VENDAS (PDV) -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="tab-pdv" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-800 text-dark mb-1"><i class="fas fa-cash-register text-success me-2"></i> Como Registrar uma Venda</h4>
                <p class="text-muted mb-4">Rápido e simples: em poucos cliques você registra qualquer pedido, seja no balcão, WhatsApp ou delivery.</p>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <h5 class="fw-bold text-dark mb-3">📋 Passo a Passo Completo:</h5>

                        <div class="d-flex mb-4">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 flex-shrink-0" style="width:36px;height:36px;">1</div>
                            <div>
                                <strong class="d-block text-dark">Escolha o Canal de Venda</strong>
                                <p class="text-muted small mb-0">No topo da tela, selecione de onde veio o pedido. Exemplos: <em>Balcão</em>, <em>WhatsApp</em> ou <em>iFood</em>. Isso é importante porque cada canal pode ter uma taxa diferente que afeta o valor que você recebe.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 flex-shrink-0" style="width:36px;height:36px;">2</div>
                            <div>
                                <strong class="d-block text-dark">Escolha a Maquininha e o Meio de Pagamento</strong>
                                <p class="text-muted small mb-0">Se o cliente vai pagar no cartão, selecione qual maquininha será usada. Depois, informe o meio: <em>Dinheiro</em>, <em>Pix</em>, <em>Débito</em> ou <em>Crédito</em>.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 flex-shrink-0" style="width:36px;height:36px;">3</div>
                            <div>
                                <strong class="d-block text-dark">Encontre e Adicione os Produtos</strong>
                                <p class="text-muted small mb-0">Use a <strong>barra de busca</strong> para digitar o nome do produto (ex: "frango") ou clique nos botões de filtro: <em>Todos</em>, <em>Produtos Próprios &amp; Bebidas</em> ou <em>Combos Promocionais</em>. Depois, <strong>clique no card do produto</strong> para adicioná-lo ao pedido.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 flex-shrink-0" style="width:36px;height:36px;">4</div>
                            <div>
                                <strong class="d-block text-dark">Ajuste Quantidades no Carrinho</strong>
                                <p class="text-muted small mb-0">No painel direito você vê o carrinho do pedido. Use os botões <code>+</code> e <code>−</code> para mudar a quantidade de cada item. Para remover um item, clique no <i class="fas fa-times text-danger"></i>.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 flex-shrink-0" style="width:36px;height:36px;">5</div>
                            <div>
                                <strong class="d-block text-dark">Identifique o Cliente (opcional)</strong>
                                <p class="text-muted small mb-0">Se o cliente for cadastrado, selecione ele no campo "Identificar Cliente". Se não, deixe em branco — a venda será registrada como balcão.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-4">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 flex-shrink-0" style="width:36px;height:36px;">6</div>
                            <div>
                                <strong class="d-block text-dark">Finalize a Venda</strong>
                                <p class="text-muted small mb-0">Clique no botão verde <strong>"Finalizar Venda &amp; Baixar Estoque"</strong>. O sistema registra a venda, desconta os ingredientes do estoque e atualiza o painel do dia!</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <h5 class="fw-bold text-dark mb-3">💡 Exemplos Práticos:</h5>

                        <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-store text-success me-2"></i> Exemplo 1 — Venda no Balcão</h6>
                            <ul class="small text-muted mb-0 ps-3">
                                <li>Canal: <strong>Balcão</strong></li>
                                <li>Maquininha: <strong>Nenhuma</strong></li>
                                <li>Pagamento: <strong>Dinheiro</strong></li>
                                <li>Produto: 1x Frango Assado — R$ 45,00</li>
                                <li>Produto: 2x Refrigerante Lata — R$ 7,00 cada</li>
                                <li><strong>Total: R$ 59,00</strong></li>
                            </ul>
                        </div>

                        <div class="card bg-light border-0 rounded-3 p-3 mb-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-motorcycle text-warning me-2"></i> Exemplo 2 — Delivery pelo iFood</h6>
                            <ul class="small text-muted mb-0 ps-3">
                                <li>Canal: <strong>iFood Delivery</strong></li>
                                <li>Maquininha: <strong>Nenhuma</strong></li>
                                <li>Pagamento: <strong>Online (pelo app)</strong></li>
                                <li>Produto: 1x Combo Família — R$ 89,90</li>
                                <li>O sistema calcula automaticamente a taxa do iFood (ex: 12%) e mostra quanto de fato entra no seu caixa.</li>
                            </ul>
                        </div>

                        <div class="card bg-light border-0 rounded-3 p-3">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-credit-card text-primary me-2"></i> Exemplo 3 — Cartão de Crédito</h6>
                            <ul class="small text-muted mb-0 ps-3">
                                <li>Canal: <strong>Balcão</strong></li>
                                <li>Maquininha: <strong>PagSeguro</strong></li>
                                <li>Pagamento: <strong>Crédito à Vista</strong></li>
                                <li>Produto: 1x Combo Família — R$ 89,90</li>
                            </ul>
                        </div>

                        <div class="alert alert-warning border-0 rounded-3 mt-3 small">
                            <strong><i class="fas fa-star me-1"></i> Dica de Ouro:</strong> Os combos mostram logo abaixo do nome quais produtos fazem parte dele. Isso ajuda a confirmar o pedido com o cliente antes de finalizar!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================ -->
        <!-- ABA 3: PRECIFICAÇÃO -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="tab-precificacao" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-800 text-dark mb-1"><i class="fas fa-calculator text-warning me-2"></i> Simulador de Precificação Inteligente</h4>
                <p class="text-muted mb-4">Chega de chutar o preço! O simulador calcula exatamente quanto você deve cobrar para cobrir todos os custos e ainda ter lucro.</p>

                <h5 class="fw-bold text-dark mb-3">🔢 Entendendo Cada Etapa:</h5>

                <div class="accordion" id="accordionPrecificacao">
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#prec1">
                                <span class="badge bg-warning text-dark me-2">1</span> Composição dos Ingredientes — Quanto custa fazer?
                            </button>
                        </h2>
                        <div id="prec1" class="accordion-collapse collapse show" data-bs-parent="#accordionPrecificacao">
                            <div class="accordion-body">
                                <p class="text-muted small mb-2">Aqui você lista tudo o que entra no produto. Por exemplo, para 1 Frango Assado:</p>
                                <table class="table table-sm table-bordered small">
                                    <thead class="table-light"><tr><th>Ingrediente</th><th>Quantidade</th><th>Custo Unitário</th><th>Subtotal</th></tr></thead>
                                    <tbody>
                                        <tr><td>Frango Cru</td><td>2,2 kg</td><td>R$ 9,50/kg</td><td>R$ 20,90</td></tr>
                                        <tr><td>Alho</td><td>30g</td><td>R$ 0,08/g</td><td>R$ 2,40</td></tr>
                                        <tr><td>Embalagem Térmica</td><td>1 un</td><td>R$ 1,20</td><td>R$ 1,20</td></tr>
                                        <tr class="table-warning"><td colspan="3"><strong>Custo dos Ingredientes</strong></td><td><strong>R$ 24,50</strong></td></tr>
                                    </tbody>
                                </table>
                                <p class="small text-muted mb-0">O sistema busca automaticamente os preços dos seus ingredientes cadastrados.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#prec2">
                                <span class="badge bg-info me-2">2</span> Rateio das Despesas Fixas — Água, Luz, Gás, Aluguel
                            </button>
                        </h2>
                        <div id="prec2" class="accordion-collapse collapse" data-bs-parent="#accordionPrecificacao">
                            <div class="accordion-body">
                                <p class="text-muted small mb-2">Suas despesas mensais são divididas entre todos os produtos que você vende. Veja o exemplo:</p>
                                <div class="bg-light p-3 rounded-3 small">
                                    <p class="mb-1">🏠 Total de despesas mensais: <strong>R$ 3.300,00</strong></p>
                                    <p class="mb-1">🍗 Frangos vendidos por mês: <strong>500 unidades</strong></p>
                                    <p class="mb-0">📊 Custo fixo por frango: <strong>R$ 3.300 ÷ 500 = R$ 6,60</strong></p>
                                </div>
                                <p class="text-muted small mt-2 mb-0">Informe no simulador quantas unidades você vende por mês para que o cálculo seja feito automaticamente.</p>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#prec3">
                                <span class="badge bg-success me-2">3</span> Margem de Lucro — Quanto você quer ganhar?
                            </button>
                        </h2>
                        <div id="prec3" class="accordion-collapse collapse" data-bs-parent="#accordionPrecificacao">
                            <div class="accordion-body">
                                <p class="text-muted small mb-2">Defina o percentual de lucro que deseja ter <strong>por cima de todos os custos</strong>. Exemplo com 40% de margem:</p>
                                <div class="bg-light p-3 rounded-3 small">
                                    <p class="mb-1">Custo total (ingredientes + despesas): <strong>R$ 31,10</strong></p>
                                    <p class="mb-1">Margem de lucro desejada: <strong>40%</strong></p>
                                    <p class="mb-0 text-success fw-bold">Preço de venda sugerido: R$ 31,10 ÷ (1 - 0,40) = <strong>R$ 51,83</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-3 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#prec4">
                                <span class="badge bg-danger me-2">4</span> Comissões de Plataformas — iFood, Cartões
                            </button>
                        </h2>
                        <div id="prec4" class="accordion-collapse collapse" data-bs-parent="#accordionPrecificacao">
                            <div class="accordion-body">
                                <p class="text-muted small mb-2">Se você vende pelo iFood ou no cartão, a plataforma fica com uma parte. O simulador ajusta o preço para que sua margem não seja prejudicada:</p>
                                <div class="bg-light p-3 rounded-3 small">
                                    <p class="mb-1">Preço base (sem taxa): <strong>R$ 51,83</strong></p>
                                    <p class="mb-1">Taxa iFood: <strong>12%</strong></p>
                                    <p class="mb-0 text-danger fw-bold">Preço ajustado para iFood: <strong>R$ 58,90</strong> (para você receber os mesmos R$ 51,83)</p>
                                </div>
                                <p class="text-muted small mt-2 mb-0">O painel da direita mostra todos esses cálculos lado a lado de forma clara.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success border-0 rounded-3 mt-3">
                    <strong><i class="fas fa-check-circle me-2"></i> Resumo do Que o Simulador Entrega:</strong>
                    <ul class="mb-0 mt-2 small">
                        <li><strong>Custo total de produção</strong> — tudo o que você gasta para fazer 1 unidade</li>
                        <li><strong>Preço mínimo de venda</strong> — abaixo disso você perde dinheiro</li>
                        <li><strong>Preço sugerido com a sua margem de lucro</strong></li>
                        <li><strong>Preço ajustado para cada plataforma</strong> (iFood 12%, iFood 27%, maquininha de cartão)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- ================================================================ -->
        <!-- ABA 4: ESTOQUE & COMPRAS -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="tab-estoque" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-800 text-dark mb-1"><i class="fas fa-boxes text-info me-2"></i> Estoque & Compras</h4>
                <p class="text-muted mb-4">Controle o que entra e o que sai da sua cozinha. Nunca mais fique sem estoque na hora errada.</p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-box text-warning me-2"></i> Produtos, Insumos & Combos</h6>
                            <p class="small text-muted mb-2">Acesse pelo menu <strong>Estoque &amp; Produtos → Produtos &amp; Combos</strong>. Aqui você tem 3 tipos de itens:</p>
                            <ul class="small text-muted mb-3">
                                <li class="mb-1"><strong>Insumo (ingrediente):</strong> frango cru, batata, sal, cebolinha. São os ingredientes que entram nas receitas.</li>
                                <li class="mb-1"><strong>Produto Acabado:</strong> frango assado, refrigerante Coca-Cola 2L. É o que você vende diretamente.</li>
                                <li><strong>Combo:</strong> agrupamento de produtos. Ex: Frango Assado + Batata Assada + Refrigerante 2L.</li>
                            </ul>
                            <div class="alert alert-warning border-0 rounded-3 small p-2">
                                <strong>Atenção:</strong> Cadastre sempre os <em>insumos</em> primeiro, antes dos combos — eles dependem uns dos outros.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-truck-loading text-primary me-2"></i> Como Registrar uma Compra</h6>
                            <p class="small text-muted mb-2">Sempre que você comprar mercadoria de um fornecedor, registre em <strong>Estoque &amp; Produtos → Entradas &amp; Compras</strong>:</p>
                            <ol class="small text-muted mb-0">
                                <li class="mb-1">Clique em <strong>"Registrar Nova Entrada"</strong></li>
                                <li class="mb-1">Escolha o <strong>produto</strong> (ex: Frango Cru)</li>
                                <li class="mb-1">Informe a <strong>quantidade</strong> comprada (ex: 50 kg)</li>
                                <li class="mb-1">Informe o <strong>fornecedor</strong> e o <strong>valor pago</strong></li>
                                <li>Confirme — o estoque aumenta automaticamente!</li>
                            </ol>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-book-open text-success me-2"></i> Fichas Técnicas (Receitas)</h6>
                            <p class="small text-muted mb-2">As fichas técnicas dizem ao sistema <strong>quais ingredientes são consumidos</strong> quando você vende um produto. Acesse em <strong>Estoque &amp; Produtos → Fichas Técnicas</strong>.</p>
                            <p class="small text-muted mb-2">Exemplo — Ficha do Frango Assado:</p>
                            <ul class="small text-muted mb-0">
                                <li>2,2 kg de Frango Cru</li>
                                <li>30g de Alho</li>
                                <li>15g de Sal</li>
                                <li>1 Embalagem Térmica</li>
                            </ul>
                            <p class="small text-muted mt-2 mb-0">Quando uma venda é finalizada no Ponto de Venda, o sistema desconta esses ingredientes do estoque automaticamente!</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Alerta de Estoque Mínimo</h6>
                            <p class="small text-muted mb-2">Para cada ingrediente, você define um <strong>estoque mínimo</strong>. Quando o estoque cair abaixo desse valor, um alerta aparece no <strong>Painel do Dia</strong>.</p>
                            <p class="small text-muted mb-0">Por exemplo: se você define que o mínimo de Frango Cru é 10 kg e o estoque cair para 8 kg, o sistema avisa imediatamente para você fazer a compra antes de acabar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================ -->
        <!-- ABA 5: FINANCEIRO -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="tab-financeiro" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-800 text-dark mb-1"><i class="fas fa-piggy-bank text-warning me-2"></i> Financeiro, Despesas & Cofrinho</h4>
                <p class="text-muted mb-4">Tenha clareza sobre o dinheiro que sai da loja e guarde uma reserva para emergências ou investimentos futuros.</p>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-file-invoice-dollar text-danger me-2"></i> Custos & Despesas Mensais</h6>
                            <p class="small text-muted mb-2">Acesse pelo menu <strong>Financeiro → Custos &amp; Despesas</strong>. Cadastre todas as suas contas:</p>
                            <ul class="small text-muted mb-3">
                                <li class="mb-1"><strong>Fixas:</strong> aluguel, internet, contador (R$ todo mês, mesmo valor)</li>
                                <li><strong>Variáveis:</strong> água (COPASA), luz (CEMIG), gás (valor que muda todo mês)</li>
                            </ul>
                            <div class="bg-light p-2 rounded-3 small">
                                <strong>Por que isso importa?</strong> O total de despesas mensais é usado pelo simulador de precificação para garantir que o preço do seu produto já cubra as contas da loja.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-coins text-warning me-2"></i> Cofrinho & Reservas Financeiras</h6>
                            <p class="small text-muted mb-2">Acesse pelo menu <strong>Financeiro → Cofrinho &amp; Reservas</strong>. Crie metas de poupança para:</p>
                            <ul class="small text-muted mb-3">
                                <li class="mb-1">Troca de equipamento (ex: novo forno a R$ 800,00)</li>
                                <li class="mb-1">Provisão de 13º salário do funcionário</li>
                                <li>Fundo de emergência (3 meses de despesas)</li>
                            </ul>
                            <p class="small text-muted mb-0">Conforme o dinheiro sobra ao final do mês, registre aportes nos cofrinhos. O sistema mostra o progresso de cada meta.</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-credit-card text-primary me-2"></i> Meios de Pagamento</h6>
                            <p class="small text-muted mb-2">Acesse pelo menu <strong>Financeiro → Meios de Pagamento</strong>. Cadastre todas as formas de recebimento que você aceita:</p>
                            <ul class="small text-muted mb-0">
                                <li class="mb-1">Dinheiro (taxa: 0%)</li>
                                <li class="mb-1">Pix direto (taxa: 0%)</li>
                                <li class="mb-1">Débito — taxa da maquininha (ex: 1,5%)</li>
                                <li>Crédito à vista — taxa da maquininha (ex: 2,5%)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-university text-secondary me-2"></i> Bancos</h6>
                            <p class="small text-muted mb-0">Cadastre suas contas bancárias (Nubank, Mercado Pago, Caixa Econômica, etc.) em <strong>Financeiro → Bancos</strong>. Essa informação é usada para registrar de qual conta o dinheiro das vendas entra.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================ -->
        <!-- ABA 6: ANÁLISES & PAINÉIS -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="tab-analises" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-800 text-dark mb-1"><i class="fas fa-chart-line text-primary me-2"></i> Análises, Painéis & Relatórios</h4>
                <p class="text-muted mb-4">Entenda o que está funcionando bem na sua loja e onde você pode melhorar.</p>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Recurso</th>
                                <th><i class="fas fa-sun text-warning me-1"></i> Painel do Dia</th>
                                <th><i class="fas fa-chart-line text-primary me-1"></i> Análises & Decisões</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr>
                                <td><strong>Objetivo Principal</strong></td>
                                <td>Acompanhar a operação <strong>agora</strong></td>
                                <td>Tomar decisões estratégicas</td>
                            </tr>
                            <tr>
                                <td><strong>Período</strong></td>
                                <td>Somente o dia de hoje</td>
                                <td>7, 30 ou 90 dias</td>
                            </tr>
                            <tr>
                                <td><strong>Comparativo</strong></td>
                                <td>Mesmo dia da semana passada</td>
                                <td>Evolução ao longo do tempo</td>
                            </tr>
                            <tr>
                                <td><strong>Gráficos</strong></td>
                                <td>Faturamento por hora; vendas por canal</td>
                                <td>Produtos mais vendidos, curva ABC, impacto de taxas</td>
                            </tr>
                            <tr>
                                <td><strong>Quando usar</strong></td>
                                <td>Ao longo do dia, no atendimento</td>
                                <td>Final de semana ou mês para planejar</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-file-alt text-secondary me-2"></i> Relatórios</h6>
                            <p class="small text-muted mb-2">Acesse pelo menu <strong>Relatórios</strong>. Você pode filtrar e exportar dados de:</p>
                            <ul class="small text-muted mb-0">
                                <li class="mb-1">Vendas por período</li>
                                <li class="mb-1">Produtos mais vendidos</li>
                                <li class="mb-1">Entrada de estoque por fornecedor</li>
                                <li>Despesas por categoria</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-lightbulb text-warning me-2"></i> Como Usar os Dados para Decidir</h6>
                            <ul class="small text-muted mb-0">
                                <li class="mb-1">Se um produto tem pouca venda, reveja o preço ou retire do cardápio.</li>
                                <li class="mb-1">Se o horário das 12h às 14h concentra 60% das vendas, aumente o estoque antes desse período.</li>
                                <li class="mb-1">Se a taxa do iFood está corroendo sua margem, use o simulador para calcular um preço compensatório.</li>
                                <li>Compare semana a semana para identificar tendências.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================ -->
        <!-- ABA 7: CADASTROS & LOJA -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="tab-cadastros" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-800 text-dark mb-1"><i class="fas fa-cogs text-secondary me-2"></i> Cadastros & Configurações da Loja</h4>
                <p class="text-muted mb-4">Configure sua loja uma vez — e o sistema usará essas informações em todas as telas automaticamente.</p>

                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-cog text-secondary me-2"></i> Dados da Loja</h6>
                            <p class="small text-muted mb-0">Acesse em <strong>Cadastros &amp; Loja → Dados da Loja</strong>. Mantenha seu nome comercial, CNPJ e endereço atualizados. Essas informações aparecem nos relatórios.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-users text-primary me-2"></i> Clientes</h6>
                            <p class="small text-muted mb-0">Cadastre seus clientes fiéis em <strong>Cadastros &amp; Loja → Clientes</strong>. Ao identificar o cliente na venda, você consegue acompanhar o histórico de compras de cada pessoa.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-building text-info me-2"></i> Fornecedores</h6>
                            <p class="small text-muted mb-0">Cadastre em <strong>Cadastros &amp; Loja → Fornecedores</strong>. Ao registrar uma compra de estoque, você vincula ao fornecedor — facilitando o controle de quem você compra cada produto.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-store text-success me-2"></i> Canais de Venda</h6>
                            <p class="small text-muted mb-0">Configure em <strong>Cadastros &amp; Loja → Canais de Venda</strong>. Crie os canais como <em>Balcão</em>, <em>WhatsApp</em>, <em>iFood</em> e defina a taxa de cada um. O sistema usa isso para calcular o valor líquido que entra no caixa.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-credit-card text-warning me-2"></i> Maquininhas</h6>
                            <p class="small text-muted mb-0">Cadastre em <strong>Cadastros &amp; Loja → Maquininhas</strong>. Se você tem mais de uma maquininha (PagSeguro, Cielo, Rede), cadastre todas. Na hora da venda, você escolhe qual foi usada.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-user-shield text-danger me-2"></i> Equipe / Profissionais</h6>
                            <p class="small text-muted mb-0">Acesse em <strong>Cadastros &amp; Loja → Equipe</strong>. Cadastre cada funcionário com nome, e-mail e senha. O sistema registra qual usuário fez cada venda — útil para controle de caixa e responsabilidade.</p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 rounded-3 mt-4 small">
                    <strong><i class="fas fa-info-circle me-2"></i> Ordem recomendada para a primeira configuração:</strong>
                    <ol class="mb-0 mt-2">
                        <li>Dados da Loja</li>
                        <li>Unidades de Medida (kg, litro, unidade, grama)</li>
                        <li>Fornecedores</li>
                        <li>Produtos & Combos (insumos primeiro, depois produtos, depois combos)</li>
                        <li>Canais de Venda e Maquininhas</li>
                        <li>Meios de Pagamento e Bancos</li>
                        <li>Custos & Despesas Mensais</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- ================================================================ -->
        <!-- ABA 8: FAQ — DÚVIDAS FREQUENTES -->
        <!-- ================================================================ -->
        <div class="tab-pane fade" id="tab-faq" role="tabpanel">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="fw-800 text-dark mb-1"><i class="fas fa-question-circle text-info me-2"></i> Dúvidas Frequentes</h4>
                <p class="text-muted mb-4">Respostas rápidas para as perguntas mais comuns do dia a dia.</p>

                <div class="accordion" id="faqAccordion">

                    <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                O estoque não baixou após uma venda. O que aconteceu?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                O estoque só é descontado automaticamente quando o produto tem uma <strong>Ficha Técnica</strong> cadastrada. Se o produto não tem ficha técnica, o sistema registra a venda mas não sabe quais ingredientes baixar. Vá em <strong>Estoque &amp; Produtos → Fichas Técnicas</strong> e cadastre os ingredientes daquele produto.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Por que o faturamento no Painel do Dia está zerado?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                Isso pode acontecer se o horário do servidor estiver errado. Verifique também se as vendas foram finalizadas corretamente no Ponto de Venda. Se o problema persistir, entre em contato com o suporte técnico.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Como cancelo ou edito uma venda que já foi finalizada?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                Por enquanto, vendas finalizadas não podem ser editadas diretamente pelo Ponto de Venda. Para corrigir, acesse a tela de <strong>Relatórios</strong>, localize a venda e consulte o administrador para o cancelamento manual. Essa funcionalidade de edição/cancelamento está planejada para versões futuras.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                O produto não aparece na tela de Ponto de Venda. Por quê?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                A tela de Ponto de Venda mostra apenas produtos do tipo <strong>Produto Acabado</strong> e <strong>Combo</strong>. Produtos do tipo <em>Insumo</em> (ingredientes) não aparecem — eles são de uso interno, não para venda direta. Verifique o tipo do produto em <strong>Estoque &amp; Produtos → Produtos &amp; Combos</strong>.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Como faço para adicionar um novo funcionário ao sistema?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                Acesse <strong>Cadastros &amp; Loja → Equipe / Profissionais</strong> e clique em <strong>"Adicionar Usuário"</strong>. Informe o nome completo, e-mail e defina uma senha inicial. O funcionário poderá fazer login com esses dados e alterar a senha depois. Apenas administradores podem gerenciar usuários.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                Como monto o preço de um combo com frango + batata + bebida?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                <ol class="mb-0">
                                    <li class="mb-1">Primeiro, certifique-se que cada produto do combo já está cadastrado individualmente com seu preço.</li>
                                    <li class="mb-1">Em <strong>Estoque &amp; Produtos → Produtos &amp; Combos</strong>, clique em <strong>"Adicionar Produto"</strong>.</li>
                                    <li class="mb-1">Selecione o tipo <strong>"Combo"</strong>.</li>
                                    <li class="mb-1">Adicione os itens: Frango Assado, Batata Assada, Refrigerante 2L.</li>
                                    <li>Defina o preço de venda do combo (geralmente menor que a soma individual para incentivar o cliente).</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                A taxa do iFood afeta o meu lucro. Como calcular o preço certo?
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                Use o <strong>Simulador de Precificação Inteligente</strong>. Após calcular o preço base, selecione a plataforma (iFood 12% ou iFood 27%). O sistema calcula automaticamente o preço que você deve cobrar dentro do app para que, após a taxa, o valor que caia na sua conta seja suficiente para cobrir custos e dar lucro.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item border-0 mb-2 shadow-sm rounded-3 overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                Como funciona o Cofrinho de Reservas?
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body small text-muted">
                                O Cofrinho é um controle virtual. Você cria um cofrinho com um nome (ex: "Reserva para Novo Forno") e uma meta (ex: R$ 800,00). Ao longo dos meses, você registra aportes (ex: "Guardei R$ 100 hoje"). O sistema mostra o progresso em direção à meta. O dinheiro não é movimentado automaticamente — é você que decide quando e quanto guardar.
                            </div>
                        </div>
                    </div>

                </div>

                <div class="alert alert-success border-0 rounded-3 mt-4">
                    <strong><i class="fas fa-headset me-2"></i> Ainda tem dúvidas?</strong>
                    <p class="mb-0 mt-1 small">Entre em contato com o suporte técnico ou administrador do sistema. Guarde este manual como referência rápida para o dia a dia!</p>
                </div>
            </div>
        </div>

    </div><!-- fim tab-content -->
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
