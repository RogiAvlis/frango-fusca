<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Fornecedor;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $dados = [
        'status_registro' => filter_input(INPUT_POST, 'status_registro', FILTER_VALIDATE_INT),
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT)),
        'cnpj_cpf' => trim(filter_input(INPUT_POST, 'cnpj_cpf', FILTER_DEFAULT)),
        'email' => trim(filter_input(INPUT_POST, 'email', FILTER_DEFAULT)),
        'telefone' => trim(filter_input(INPUT_POST, 'telefone', FILTER_DEFAULT)),
        'endereco' => trim(filter_input(INPUT_POST, 'endereco', FILTER_DEFAULT))
    ];

    $conn = Conexao::obterConexao();
    
    $fornecedor = new Fornecedor();
    if ($fornecedor->cadastrar($conn, $dados)) {
        echo json_encode(['status' => 'success', 'message' => 'Fornecedor cadastrado com sucesso!']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao cadastrar fornecedor.']);
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
