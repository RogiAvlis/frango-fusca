<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Cliente;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

require_once __DIR__ . '/../../src/core/verificar_sessao.php';

try {
    $idRegistro = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $dados = [
        'status_registro' => filter_input(INPUT_POST, 'status_registro', FILTER_VALIDATE_INT),
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT)),
        'telefone' => trim(filter_input(INPUT_POST, 'telefone', FILTER_DEFAULT))
    ];
    $idUsuario = $_SESSION['user_id'];

    $conn = Conexao::obterConexao();
    
    $cliente = new Cliente();
    if ($cliente->editar($conn, $idRegistro, $dados, $idUsuario)) {
        echo json_encode(['status' => 'success', 'message' => 'Cliente atualizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'Nenhuma alteração foi feita (dados iguais).']);
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
