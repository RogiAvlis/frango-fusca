<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\ItemVenda;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $dados = [
        'status_registro' => filter_input(INPUT_POST, 'status_registro', FILTER_VALIDATE_INT),
        'venda_id' => filter_input(INPUT_POST, 'venda_id', FILTER_VALIDATE_INT),
        'produto_id' => filter_input(INPUT_POST, 'produto_id', FILTER_VALIDATE_INT),
        'quantidade' => filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT),
        'preco_venda' => filter_input(INPUT_POST, 'preco_venda', FILTER_VALIDATE_FLOAT)
    ];

    $conn = Conexao::obterConexao();
    
    if (ItemVenda::editar($conn, $id, $dados)) {
        echo json_encode(['status' => 'success', 'message' => 'Item de venda atualizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'Nenhuma alteração foi feita (dados iguais).']);
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
