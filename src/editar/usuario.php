<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Usuario;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('POST');

try {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $dados = [
        'nome' => trim(filter_input(INPUT_POST, 'nome', FILTER_DEFAULT)),
        'email' => trim(filter_input(INPUT_POST, 'email', FILTER_DEFAULT)),
        'senha' => filter_input(INPUT_POST, 'senha', FILTER_DEFAULT), // Pode ser vazio se não for alterada
        'status_registro' => filter_input(INPUT_POST, 'status_registro', FILTER_VALIDATE_INT)
    ];

    $conn = Conexao::obterConexao();
    
    $usuario = new Usuario();
    if ($usuario->editar($conn, $id, $dados)) {
        echo json_encode(['status' => 'success', 'message' => 'Usuário atualizado com sucesso!']);
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
