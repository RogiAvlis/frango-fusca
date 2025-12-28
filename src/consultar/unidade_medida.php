<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\UnidadeMedida;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    
    $unidadeMedida = new UnidadeMedida();
    $unidades = $unidadeMedida->listar($conn);
    
    // Formata a saída para o DataTables, dentro de um objeto "data"
    echo json_encode(['data' => $unidades]);

} catch (\Exception $e) {
    // Em caso de erro, retorna um JSON de erro
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'error' => 'Erro ao consultar unidades de medida.',
        'message' => $e->getMessage()
    ]);
}
