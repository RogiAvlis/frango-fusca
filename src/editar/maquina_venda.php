<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\MaquinaVenda;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $dados = [
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT)),
        'descricao' => trim(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
        'taxa' => filter_input(INPUT_POST, 'taxa', FILTER_VALIDATE_FLOAT)
    ];

    $conn = Conexao::obterConexao();
    
    if (MaquinaVenda::editar($conn, $id, $dados)) {
        echo json_encode(['status' => 'success', 'message' => 'Máquina de venda atualizada com sucesso!']);
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
