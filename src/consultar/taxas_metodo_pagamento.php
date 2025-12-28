<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\MetodoPagamento;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $metodoPagamento = new MetodoPagamento();
    $metodos = $metodoPagamento->listar($conn);
    
    // Formata os dados para a simulação: id, texto (nome), e a taxa (que é 0.0 por padrão)
    $resultado = array_map(function($metodo) {
        return [
            'id' => $metodo['id'],
            'text' => $metodo['nome'],
            'taxa' => 0.0 // Tabela metodo_pagamento não tem coluna de taxa
        ];
    }, $metodos);

    echo json_encode($resultado);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar métodos de pagamento para simulação.',
        'message' => $e->getMessage()
    ]);
}
