<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Cliente;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $clientes = Cliente::listar($conn);
    
    // Formata os dados para o select: id, texto (nome)
    $resultado = array_map(function($cliente) {
        return [
            'id' => $cliente['id'],
            'text' => $cliente['nome']
        ];
    }, $clientes);

    echo json_encode($resultado);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar clientes.',
        'message' => $e->getMessage()
    ]);
}
