<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FrangoFusca\Entidades\UnidadeMedida;
use FrangoFusca\Db\Conexao;
use function FrangoFusca\Helpers\verificarMetodo;

verificarMetodo('GET');

try {
    $conn = Conexao::obterConexao();
    $unidades = UnidadeMedida::listar($conn);
    
    // Formata os dados para o select: id, texto (sigla - nome)
    $resultado = array_map(function($unidade) {
        return [
            'id' => $unidade['id'],
            'text' => $unidade['sigla'] . ' - ' . $unidade['nome']
        ];
    }, $unidades);

    echo json_encode($resultado);

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Erro ao consultar unidades de medida.',
        'message' => $e->getMessage()
    ]);
}
