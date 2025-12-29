<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\AmbienteVenda;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

require_once __DIR__ . '/../../src/core/verificar_sessao.php';

try {
    $dados = [
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT)),
        'descricao' => trim(filter_input(INPUT_POST, 'descricao', FILTER_DEFAULT)),
        'taxa' => filter_input(INPUT_POST, 'taxa', FILTER_VALIDATE_FLOAT)
    ];
    $idUsuario = $_SESSION['user_id'];

    $conn = Conexao::obterConexao();
    
    $ambienteVenda = new AmbienteVenda();
    if ($ambienteVenda->cadastrar($conn, $dados, $idUsuario)) {
        echo json_encode(['status' => 'success', 'message' => 'Ambiente de venda cadastrado com sucesso!']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao cadastrar ambiente de venda.']);
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
