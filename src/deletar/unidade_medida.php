<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\UnidadeMedida;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $conn = Conexao::obterConexao();
    
    if (UnidadeMedida::deletar($conn, $id)) {
        echo json_encode(['status' => 'success', 'message' => 'Unidade de medida deletada com sucesso!']);
    } else {
        // Esta condição é menos provável com a validação de ID dentro do método
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao deletar unidade de medida.']);
    }

} catch (\Exception $e) {
    // Captura exceções de validação (ID não encontrado) ou do banco de dados
    $codigo = $e->getCode() == 0 ? 400 : $e->getCode();
    if ($codigo > 599 || $codigo < 100) $codigo = 500;
    http_response_code($codigo);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
