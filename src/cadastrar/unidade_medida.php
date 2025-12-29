<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\UnidadeMedida;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

require_once __DIR__ . '/../../src/core/verificar_sessao.php';

try {
    // Captura os dados de entrada
    $dados = [
        'sigla' => trim(filter_input(INPUT_POST, 'sigla', FILTER_DEFAULT)),
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT))
    ];
    $idUsuario = $_SESSION['user_id'];

    $conn = Conexao::obterConexao();
    
    $unidadeMedida = new UnidadeMedida();
    if ($unidadeMedida->cadastrar($conn, $dados, $idUsuario)) {
        echo json_encode(['status' => 'success', 'message' => 'Unidade de medida cadastrada com sucesso!']);
    } else {
        // Esta condição é menos provável de acontecer se a exceção for o principal meio de falha
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falha ao cadastrar unidade de medida.']);
    }

} catch (\Exception $e) {
    // Captura exceções de validação ou do banco de dados
    $codigo = $e->getCode() == 0 ? 400 : $e->getCode(); // 400 para erros de validação
    http_response_code($codigo);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
