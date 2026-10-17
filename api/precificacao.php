<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/session_check.php';
require_once __DIR__ . '/../config/database.php';

use FrangoFusca\Models\Produto;
use FrangoFusca\Models\AmbienteVenda;
use FrangoFusca\Models\MaquinaVenda;

$estabelecimentoId = $_SESSION['estabelecimento_id'];

// Retorna todos os dados pré-carregados do banco para abastecer a tela de simulação
$produtos = Produto::listarComDetalhes($estabelecimentoId);
$canais = AmbienteVenda::listarTodos($estabelecimentoId);
$maquinas = MaquinaVenda::listarTodos($estabelecimentoId);

// Buscar custos mensais fixos para rateio sugestivo
$db = \FrangoFusca\Database\Connection::getInstance();
$stmtCustos = $db->prepare("SELECT SUM(valor) as total_fixo FROM custo_mensal WHERE estabelecimento_id = :id AND tipo_custo = 'fixo' AND status_registro = 1");
$stmtCustos->execute([':id' => $estabelecimentoId]);
$custosFixos = (float)($stmtCustos->fetch()['total_fixo'] ?? 0);

echo json_encode([
    'success' => true,
    'data' => [
        'produtos' => $produtos,
        'canais' => $canais,
        'maquinas' => $maquinas,
        'total_custos_fixos_mensais' => $custosFixos
    ]
]);
