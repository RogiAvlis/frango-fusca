<?php

namespace FrangoFusca\Core;

class DashboardVendas
{
    /**
     * Obtém dados de vendas agregados por um período (dia, mês ou ano).
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param string $tipo_periodo Tipo de período ('dia', 'mes', 'ano').
     * @param string|null $data_inicio Data de início (YYYY-MM-DD).
     * @param string|null $data_fim Data de fim (YYYY-MM-DD).
     * @return array Retorna um array com os dados para o gráfico.
     */
    public static function getVendasPorPeriodo(\PDO $conn, string $tipo_periodo, ?string $data_inicio = null, ?string $data_fim = null): array
    {
        $filtro = '';
        $valores = [];
        $formato_data = '';

        switch ($tipo_periodo) {
            case 'mes':
                $formato_data = '%Y-%m';
                break;
            case 'ano':
                $formato_data = '%Y';
                break;
            case 'dia':
            default:
                $formato_data = '%Y-%m-%d';
                break;
        }

        if ($data_inicio && $data_fim) {
            $filtro = 'WHERE data_venda BETWEEN ? AND ?';
            $valores = [$data_inicio . ' 00:00:00', $data_fim . ' 23:59:59'];
        }

        $sql = "SELECT 
                    DATE_FORMAT(data_venda, '{$formato_data}') as periodo,
                    SUM(valor_total) as total,
                    COUNT(id) as quantidade
                FROM venda
                {$filtro}
                GROUP BY periodo
                ORDER BY periodo ASC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valores);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Agrega vendas por método de pagamento em um período.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param string|null $data_inicio Data de início (YYYY-MM-DD).
     * @param string|null $data_fim Data de fim (YYYY-MM-DD).
     * @return array Retorna um array com os dados para o gráfico.
     */
    public static function getVendasPorMetodoPagamento(\PDO $conn, ?string $data_inicio = null, ?string $data_fim = null): array
    {
        $filtro = '';
        $valores = [];

        if ($data_inicio && $data_fim) {
            $filtro = 'WHERE v.data_venda BETWEEN ? AND ?';
            $valores = [$data_inicio . ' 00:00:00', $data_fim . ' 23:59:59'];
        }

        $sql = "SELECT 
                    mp.nome as label,
                    SUM(v.valor_total) as total
                FROM venda v
                JOIN metodo_pagamento mp ON v.metodo_pagamento_id = mp.id
                {$filtro}
                GROUP BY mp.nome
                ORDER BY total DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valores);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Agrega vendas por ambiente de venda em um período.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param string|null $data_inicio Data de início (YYYY-MM-DD).
     * @param string|null $data_fim Data de fim (YYYY-MM-DD).
     * @return array Retorna um array com os dados para o gráfico.
     */
    public static function getVendasPorAmbienteVenda(\PDO $conn, ?string $data_inicio = null, ?string $data_fim = null): array
    {
        $filtro = '';
        $valores = [];

        if ($data_inicio && $data_fim) {
            $filtro = 'WHERE v.data_venda BETWEEN ? AND ?';
            $valores = [$data_inicio . ' 00:00:00', $data_fim . ' 23:59:59'];
        }

        $sql = "SELECT 
                    av.nome as label,
                    SUM(v.valor_total) as total
                FROM venda v
                JOIN ambiente_venda av ON v.ambiente_venda_id = av.id
                {$filtro}
                GROUP BY av.nome
                ORDER BY total DESC";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valores);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Obtém os produtos mais vendidos em um período.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param string|null $data_inicio Data de início (YYYY-MM-DD).
     * @param string|null $data_fim Data de fim (YYYY-MM-DD).
     * @param int $limite Número de produtos a serem retornados.
     * @return array Retorna um array com os produtos mais vendidos.
     */
    public static function getTopProdutosVendidos(\PDO $conn, ?string $data_inicio = null, ?string $data_fim = null, int $limite = 5): array
    {
        $filtro = '';
        $valores = [];

        if ($data_inicio && $data_fim) {
            $filtro = 'WHERE v.data_venda BETWEEN ? AND ?';
            $valores = [$data_inicio . ' 00:00:00', $data_fim . ' 23:59:59'];
        }

        $sql = "SELECT 
                    p.nome as label,
                    SUM(iv.quantidade) as quantidade
                FROM item_venda iv
                JOIN venda v ON iv.venda_id = v.id
                JOIN produto p ON iv.produto_id = p.id
                {$filtro}
                GROUP BY p.nome
                ORDER BY quantidade DESC
                LIMIT {$limite}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valores);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
