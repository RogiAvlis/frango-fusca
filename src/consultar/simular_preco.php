<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Core\SimuladorPreco;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST'); // Recebe os dados via POST para mais segurança e flexibilidade

try {
    $dados = [
        'produto_id' => filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT),
        'preco_venda_sugerido' => filter_input(INPUT_POST, 'preco_venda_sugerido', FILTER_VALIDATE_FLOAT),
        'ambiente_venda_id' => filter_input(INPUT_POST, 'ambiente_venda_id', FILTER_VALIDATE_INT),
        'metodo_pagamento_id' => filter_input(INPUT_POST, 'metodo_pagamento_id', FILTER_VALIDATE_INT)
    ];

    if (empty($dados['produto_id']) || !isset($dados['preco_venda_sugerido'])) {
        throw new \Exception("ID do produto e preço de venda sugerido são obrigatórios.", 400);
    }
    
    $conn = Conexao::obterConexao();

    // Buscar os dados necessários
    $preco_custo = SimuladorPreco::buscarPrecoCustoProdutoPorId($conn, $dados['produto_id']);
    $taxa_ambiente = !empty($dados['ambiente_venda_id']) ? SimuladorPreco::buscarTaxaAmbientePorId($conn, $dados['ambiente_venda_id']) : 0.0;
    $taxa_metodo_pagamento = !empty($dados['metodo_pagamento_id']) ? SimuladorPreco::buscarTaxaMetodoPagamentoPorId($conn, $dados['metodo_pagamento_id']) : 0.0;
    
    // Chamar o método de cálculo
    $resultado = SimuladorPreco::calcularMargem(
        $preco_custo,
        $dados['preco_venda_sugerido'],
        $taxa_ambiente,
        $taxa_metodo_pagamento
    );

    echo json_encode($resultado);

} catch (\Exception $e) {
    $codigo = $e->getCode() == 0 ? 400 : $e->getCode();
    if ($codigo > 599 || $codigo < 100) $codigo = 500;
    http_response_code($codigo);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
