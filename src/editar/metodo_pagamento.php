<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\MetodoPagamento;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $dados = [
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT)),
        'banco' => trim(filter_input(INPUT_POST, 'banco', FILTER_DEFAULT)),
        'agencia' => trim(filter_input(INPUT_POST, 'agencia', FILTER_DEFAULT)),
        'conta' => trim(filter_input(INPUT_POST, 'conta', FILTER_DEFAULT))
    ];

    $conn = Conexao::obterConexao();
    
    $metodoPagamento = new MetodoPagamento();
    if ($metodoPagamento->editar($conn, $id, $dados)) {
        echo json_encode(['status' => 'success', 'message' => 'Método de pagamento atualizado com sucesso!']);
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
