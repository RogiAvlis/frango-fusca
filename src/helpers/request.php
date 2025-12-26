<?php

namespace FrangoFusca\Helpers;

/**
 * Verifica se o método da requisição HTTP corresponde ao método esperado.
 * Se não corresponder, define o código de resposta para 405, exibe uma
 * mensagem de erro em JSON e encerra o script.
 *
 * @param string $metodoEsperado O método HTTP esperado (ex: 'GET', 'POST').
 */
function verificarMetodo(string $metodoEsperado): void
{
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($metodoEsperado)) {
        http_response_code(405); // Método não permitido
        echo json_encode(['error' => "Método não permitido. Apenas {$metodoEsperado} é aceito."]);
        exit;
    }
}
