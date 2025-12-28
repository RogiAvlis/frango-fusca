<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\CustoMensal;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $dados = [
        'descricao' => trim(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
        'valor' => filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT),
        'data_pagamento' => filter_input(INPUT_POST, 'data_pagamento', FILTER_DEFAULT),
        'quantidade_parcela' => filter_input(INPUT_POST, 'quantidade_parcela', FILTER_VALIDATE_INT),
        'mes' => filter_input(INPUT_POST, 'mes', FILTER_VALIDATE_INT),
        'ano' => filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT),
        'tipo_custo' => filter_input(INPUT_POST, 'tipo_custo', FILTER_DEFAULT),
        'status_pagamento' => filter_input(INPUT_POST, 'status_pagamento', FILTER_VALIDATE_INT)
    ];

    $conn = Conexao::obterConexao();
    
    $custoMensal = new CustoMensal();
    if ($custoMensal->editar($conn, $id, $dados)) {
        echo json_encode(['status' => 'success', 'message' => 'Custo mensal atualizado com sucesso!']);
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
