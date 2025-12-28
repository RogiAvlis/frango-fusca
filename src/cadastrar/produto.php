<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Produto;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $dados = [
        'status_registro' => filter_input(INPUT_POST, 'status_registro', FILTER_VALIDATE_INT),
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT)),
        'descricao' => trim(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
        'preco_custo' => filter_input(INPUT_POST, 'preco_custo', FILTER_VALIDATE_FLOAT),
        'preco_venda' => filter_input(INPUT_POST, 'preco_venda', FILTER_VALIDATE_FLOAT),
        'quantidade_comprada' => filter_input(INPUT_POST, 'quantidade_comprada', FILTER_VALIDATE_INT),
        'unidade_medida_id' => filter_input(INPUT_POST, 'unidade_medida_id', FILTER_VALIDATE_INT),
        'fornecedor_id' => filter_input(INPUT_POST, 'fornecedor_id', FILTER_VALIDATE_INT)
    ];

    $conn = Conexao::obterConexao();
    
    $produto = new Produto();
    if ($produto->cadastrar($conn, $dados)) {
        echo json_encode(['status' => 'success', 'message' => 'Produto cadastrado com sucesso!']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao cadastrar produto.']);
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
