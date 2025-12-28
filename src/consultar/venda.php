<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Venda;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $vendas = Venda::listar($conn);
    echo json_encode(['data' => $vendas]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar vendas.',
        'message' => $e->getMessage()
    ]);
}
