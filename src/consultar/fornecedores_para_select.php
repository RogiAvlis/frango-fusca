<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Fornecedor;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $fornecedor = new Fornecedor();
    $fornecedores = $fornecedor->listar($conn);
    
    // Formata os dados para o select: id, texto (nome)
    $resultado = array_map(function($fornecedor) {
        return [
            'id' => $fornecedor['id'],
            'text' => $fornecedor['nome']
        ];
    }, $fornecedores);

    echo json_encode($resultado);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar fornecedores.',
        'message' => $e->getMessage()
    ]);
}
