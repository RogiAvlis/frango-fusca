<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Venda;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

require_once __DIR__ . '/../../src/core/verificar_sessao.php';

try {
    $idRegistro = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $dados = [
        'cliente_id' => filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT),
        'vendedor_id' => filter_input(INPUT_POST, 'vendedor_id', FILTER_VALIDATE_INT),
        'data_venda' => trim(filter_input(INPUT_POST, 'data_venda', FILTER_DEFAULT)),
        'valor_total' => filter_input(INPUT_POST, 'valor_total', FILTER_VALIDATE_FLOAT),
        'metodo_pagamento_id' => filter_input(INPUT_POST, 'metodo_pagamento_id', FILTER_VALIDATE_INT),
        'ambiente_venda_id' => filter_input(INPUT_POST, 'ambiente_venda_id', FILTER_VALIDATE_INT)
    ];
    $idUsuario = $_SESSION['user_id'];

    $conn = Conexao::obterConexao();
    
    $venda = new Venda();
    if ($venda->editar($conn, $idRegistro, $dados, $idUsuario)) {
        echo json_encode(['status' => 'success', 'message' => 'Venda atualizada com sucesso!']);
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
