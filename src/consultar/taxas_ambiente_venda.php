<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\AmbienteVenda;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $ambientes = AmbienteVenda::listar($conn);
    
    // Formata os dados para a simulação: id, texto (nome), e a taxa
    $resultado = array_map(function($ambiente) {
        return [
            'id' => $ambiente['id'],
            'text' => $ambiente['nome'],
            'taxa' => $ambiente['taxa']
        ];
    }, $ambientes);

    echo json_encode($resultado);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar ambientes de venda para simulação.',
        'message' => $e->getMessage()
    ]);
}
