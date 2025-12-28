<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Receita;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $conn = Conexao::obterConexao();
    
    if (Receita::deletar($conn, $id)) {
        echo json_encode(['status' => 'success', 'message' => 'Receita deletada com sucesso!']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao deletar receita.']);
    }

} catch (\Exception $e) {
    $codigo = $e->getCode() == 0 ? 400 : $e->getCode();
    if ($codigo > 599 || $codigo < 100) $codigo = 500;
    http_response_code($codigo);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
