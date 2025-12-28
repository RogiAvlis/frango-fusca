<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\CustoMensal;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $custoMensal = new CustoMensal();
    $custos = $custoMensal->listar($conn);
    echo json_encode(['data' => $custos]);
} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar custos mensais.',
        'message' => $e->getMessage()
    ]);
}
