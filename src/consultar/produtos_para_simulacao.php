<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Produto;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $produtos = Produto::listar($conn);
    
    // Formata os dados para a simulação: id, texto (nome), e o preço de custo
    $resultado = array_map(function($produto) {
        return [
            'id' => $produto['id'],
            'text' => $produto['nome'],
            'preco_custo' => $produto['preco_custo']
        ];
    }, $produtos);

    echo json_encode($resultado);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar produtos para simulação.',
        'message' => $e->getMessage()
    ]);
}
