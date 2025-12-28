<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use FrangoFusca\Core\DashboardVendas;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $tipo_periodo = filter_input(INPUT_GET, 'tipo_periodo', FILTER_DEFAULT);
    $data_inicio = filter_input(INPUT_GET, 'data_inicio', FILTER_DEFAULT);
    $data_fim = filter_input(INPUT_GET, 'data_fim', FILTER_DEFAULT);
    
    if (empty($tipo_periodo)) {
        throw new \Exception("O tipo de período é obrigatório.", 400);
    }

    $conn = Conexao::obterConexao();
    $dados = DashboardVendas::getVendasPorPeriodo($conn, $tipo_periodo, $data_inicio, $data_fim);
    
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
