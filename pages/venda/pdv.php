<?php
require_once __DIR__ . '/../../config/session_check.php';
$page_title = 'Ponto de Venda';
include __DIR__ . '/../../templates/header.php';
?>

<main class="container-fluid px-4 py-4">
    <!-- Cabeçalho -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-800 text-dark mb-0"><i class="fas fa-cash-register text-success me-2"></i> Ponto de Venda</h2>
            <p class="text-muted small mb-0">Emissão rápida de vendas e pedidos com baixa automática no estoque</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Coluna Esquerda: Filtros, Busca & Catálogo de Produtos -->
        <div class="col-lg-7 col-xl-8">
            <!-- Configurações do Pedido: Canal, Maquininha e Meio de Pagamento -->
            <div class="card border-0 shadow-sm p-3 mb-3 bg-white">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <label for="pdv-canal" class="form-label fw-600 mb-1 small">Canal / Plataforma</label>
                        <select class="form-select form-select-sm" id="pdv-canal"></select>
                    </div>
                    <div class="col-md-4">
                        <label for="pdv-maquina" class="form-label fw-600 mb-1 small">Maquininha de Cartão</label>
                        <select class="form-select form-select-sm" id="pdv-maquina">
                            <option value="">Nenhuma / Dinheiro ou Pix Direto</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="pdv-metodo" class="form-label fw-600 mb-1 small">Meio de Pagamento</label>
                        <select class="form-select form-select-sm" id="pdv-metodo"></select>
                    </div>
                </div>
            </div>

            <!-- Barra de Busca de Produtos & Filtro de Categorias Simplificado -->
            <div class="card border-0 shadow-sm p-3 mb-3 bg-white">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" id="pdv-busca-produto" class="form-control border-start-0" placeholder="Digite para buscar um item pelo nome...">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-md-end gap-1" id="pdv-filtros-categoria">
                        <button type="button" class="btn btn-sm btn-primary btn-filtro-cat active" data-cat="todos">Todos</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-filtro-cat" data-cat="acabado">Produtos Próprios & Bebidas</button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-filtro-cat" data-cat="combo">Combos Promocionais</button>
                    </div>
                </div>
            </div>

            <!-- Grid de Produtos Selecionáveis -->
            <div class="row g-3" id="grid-produtos-pdv">
                <!-- Cards de produtos gerados via JS -->
            </div>
        </div>

        <!-- Coluna Direita: Carrinho do Pedido & Fechamento -->
        <div class="col-lg-5 col-xl-4">
            <div class="card border-0 shadow-lg sticky-top" style="top: 80px;">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-warning"><i class="fas fa-shopping-basket me-2"></i> Detalhes do Pedido</h6>
                    <button type="button" class="btn btn-sm btn-outline-light" id="btn-limpar-carrinho"><i class="fas fa-trash me-1"></i> Limpar</button>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <label for="pdv-cliente" class="form-label fw-600 small">Identificar Cliente (Opcional)</label>
                        <select class="form-select form-select-sm" id="pdv-cliente">
                            <option value="">Cliente Não Identificado (Balcão)</option>
                        </select>
                    </div>

                    <div id="carrinho-itens-list" class="my-3 overflow-auto" style="max-height: 320px;">
                        <!-- Lista de itens do carrinho -->
                    </div>

                    <div class="bg-light p-3 rounded border text-center mb-3">
                        <span class="text-muted small uppercase fw-bold">Total do Pedido</span>
                        <h2 class="fw-800 text-success mb-0 mt-1" id="pdv-total-carrinho">R$ 0,00</h2>
                    </div>

                    <button type="button" class="btn btn-success btn-lg w-100 fw-bold shadow-sm py-3" id="btn-finalizar-venda">
                        <i class="fas fa-check-circle me-2"></i> Finalizar Venda & Baixar Estoque
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../../templates/footer.php'; ?>
<script type="module" src="../../assets/js/pdv/index.js"></script>
