<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Receita;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $conn = Conexao::obterConexao();
    $receita = Receita::buscarPorId($conn, $id);
    
    if ($receita) {
        echo json_encode($receita);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Receita não encontrada.']);
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
