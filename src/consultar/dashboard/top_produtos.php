<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use FrangoFusca\Core\DashboardVendas;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $data_inicio = filter_input(INPUT_GET, 'data_inicio', FILTER_DEFAULT);
    $data_fim = filter_input(INPUT_GET, 'data_fim', FILTER_DEFAULT);
    $limite = filter_input(INPUT_GET, 'limite', FILTER_VALIDATE_INT) ?: 5; // Padrão 5 se não for fornecido

    $conn = Conexao::obterConexao();
    $dados = DashboardVendas::getTopProdutosVendidos($conn, $data_inicio, $data_fim, $limite);
    
    echo json_encode($dados);

} catch (\Exception $e) {
    $codigo = $e->getCode() == 0 ? 400 : $e->getCode();
    if ($codigo > 599 || $codigo < 100) $codigo = 500;
    http_response_code($codigo);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
