<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\Usuario;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $usuario = new Usuario();
    $usuarios = $usuario->listar($conn); // Assume que qualquer usuário pode ser vendedor, ou o filtro é no frontend
    
    // Formata os dados para o select: id, texto (nome)
    $resultado = array_map(function($usuario) {
        return [
            'id' => $usuario['id'],
            'text' => $usuario['nome']
        ];
    }, $usuarios);

    echo json_encode($resultado);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar vendedores.',
        'message' => $e->getMessage()
    ]);
}
