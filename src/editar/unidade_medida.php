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
    $idRegistro = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $dados = [
        'sigla' => trim(filter_input(INPUT_POST, 'sigla', FILTER_DEFAULT)),
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT))
    ];
    $idUsuario = $_SESSION['user_id'];

    $conn = Conexao::obterConexao();
    
    $unidadeMedida = new UnidadeMedida();
    if ($unidadeMedida->editar($conn, $idRegistro, $dados, $idUsuario)) {
        echo json_encode(['status' => 'success', 'message' => 'Unidade de medida atualizada com sucesso!']);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'Nenhuma alteração foi feita (dados iguais).']);
    }

} catch (\Exception $e) {
    // Captura exceções de validação ou do banco de dados
    $codigo = $e->getCode() == 0 ? 400 : $e->getCode(); // Default 400 para erros de validação
    if ($codigo > 599 || $codigo < 100) $codigo = 500; // Garante um código http válido
    http_response_code($codigo);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
