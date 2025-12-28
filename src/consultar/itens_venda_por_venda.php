<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\ItemVenda;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $vendaId = filter_input(INPUT_GET, 'venda_id', FILTER_VALIDATE_INT);

    if (empty($vendaId)) {
        throw new \Exception("ID da venda é obrigatório.", 400);
    }

    $conn = Conexao::obterConexao();
    $itens = ItemVenda::listarPorVenda($conn, $vendaId);
    echo json_encode(['data' => $itens]);
} catch (\Exception $e) {
    $codigo = $e->getCode() == 0 ? 400 : $e->getCode();
    if ($codigo > 599 || $codigo < 100) $codigo = 500;
    http_response_code($codigo);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
