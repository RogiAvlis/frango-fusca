<?php

namespace FrangoFusca\Core;

use FrangoFusca\Entidades\AmbienteVenda;
use FrangoFusca\Entidades\MetodoPagamento;
use FrangoFusca\Entidades\Produto;

class SimuladorPreco
{
    /**
     * Calcula a margem de lucro e outros dados com base nos preços e taxas.
     *
     * @param float $preco_custo Preço de custo do produto.
     * @param float $preco_venda Preço de venda sugerido.
     * @param float $taxa_ambiente Taxa percentual do ambiente de venda.
     * @param float $taxa_metodo_pagamento Taxa percentual do método de pagamento.
     * @return array Retorna um array com os resultados da simulação.
     */
    public static function calcularMargem(float $preco_custo, float $preco_venda, float $taxa_ambiente = 0.0, float $taxa_metodo_pagamento = 0.0): array
    {
        // Calcula o valor das taxas sobre o preço de venda
        $valor_taxa_ambiente = $preco_venda * ($taxa_ambiente / 100);
        $valor_taxa_metodo_pagamento = $preco_venda * ($taxa_metodo_pagamento / 100);

        // Calcula o custo final
        $custo_final = $preco_custo + $valor_taxa_ambiente + $valor_taxa_metodo_pagamento;

        // Calcula o lucro
        $lucro_valor = $preco_venda - $custo_final;
        $lucro_percentual = ($preco_venda > 0) ? ($lucro_valor / $preco_venda) * 100 : 0;

        return [
            'preco_custo' => (float)$preco_custo,
            'preco_venda' => (float)$preco_venda,
            'taxa_ambiente_percentual' => (float)$taxa_ambiente,
            'taxa_metodo_pagamento_percentual' => (float)$taxa_metodo_pagamento,
            'valor_taxa_ambiente' => (float)$valor_taxa_ambiente,
            'valor_taxa_metodo_pagamento' => (float)$valor_taxa_metodo_pagamento,
            'custo_final' => (float)$custo_final,
            'lucro_valor' => (float)$lucro_valor,
            'lucro_percentual' => (float)$lucro_percentual
        ];
    }

    /**
     * Busca a taxa de um ambiente de venda pelo ID.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int $ambiente_venda_id ID do ambiente de venda.
     * @return float Retorna a taxa ou 0.0 se não encontrado.
     */
    public static function buscarTaxaAmbientePorId(\PDO $conn, int $ambiente_venda_id): float
    {
        $ambiente = AmbienteVenda::buscarPorId($conn, $ambiente_venda_id);
        return $ambiente ? (float)$ambiente['taxa'] : 0.0;
    }

    /**
     * Busca a taxa de um método de pagamento pelo ID.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int $metodo_pagamento_id ID do método de pagamento.
     * @return float Retorna a taxa ou 0.0 se não encontrado. (A tabela metodo_pagamento não tem taxa, mas a de maquina_venda tem, pode ser uma confusão na tarefa)
     */
    public static function buscarTaxaMetodoPagamentoPorId(\PDO $conn, int $metodo_pagamento_id): float
    {
        // A tabela metodo_pagamento não tem taxa. Se a intenção era usar maquina_venda, a lógica deveria ser ajustada.
        // Por enquanto, retornando 0.0 para evitar erros.
        return 0.0;
    }

    /**
     * Busca o preço de custo de um produto pelo ID.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int $produto_id ID do produto.
     * @return float Retorna o preço de custo ou 0.0 se não encontrado.
     */
    public static function buscarPrecoCustoProdutoPorId(\PDO $conn, int $produto_id): float
    {
        $produto = Produto::buscarPorId($conn, $produto_id);
        return $produto ? (float)$produto['preco_custo'] : 0.0;
    }
}
