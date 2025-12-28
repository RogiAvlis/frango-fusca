<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Usuario;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $dados = [
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT)),
        'email' => trim(filter_input(INPUT_POST, 'email', FILTER_DEFAULT)),
        'senha' => filter_input(INPUT_POST, 'senha', FILTER_DEFAULT),
        'status_registro' => filter_input(INPUT_POST, 'status_registro', FILTER_VALIDATE_INT)
    ];

    $conn = Conexao::obterConexao();
    
    if (Usuario::cadastrar($conn, $dados)) {
        echo json_encode(['status' => 'success', 'message' => 'Usuário cadastrado com sucesso!']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao cadastrar usuário.']);
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
