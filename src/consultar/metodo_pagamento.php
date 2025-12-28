<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\MetodoPagamento;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $metodoPagamento = new MetodoPagamento();
    $metodos = $metodoPagamento->listar($conn);
    echo json_encode(['data' => $metodos]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar métodos de pagamento.',
        'message' => $e->getMessage()
    ]);
}
