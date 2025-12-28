<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class CustoMensal implements IEntidade
{
    private static string $tabela = 'custo_mensal';

    /**
     * Valida os dados para cadastro ou edição de um custo mensal.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro (ignorado nesta implementação).
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];
        $camposObrigatorios = ['tipo_custo', 'descricao', 'valor', 'data_pagamento', 'mes', 'ano'];

        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                $erros[$campo] = "O campo '$campo' é obrigatório.";
            }
        }

        if (isset($dados['tipo_custo']) && !in_array($dados['tipo_custo'], ['fixo', 'variavel'])) {
            $erros['tipo_custo'] = "O tipo de custo deve ser 'fixo' ou 'variavel'.";
        }

        if (isset($dados['valor']) && (!is_numeric($dados['valor']) || (float)$dados['valor'] < 0)) {
            $erros['valor'] = 'O valor deve ser numérico e não negativo.';
        }

        if (isset($dados['mes']) && (!is_numeric($dados['mes']) || (int)$dados['mes'] < 1 || (int)$dados['mes'] > 12)) {
            $erros['mes'] = 'Mês inválido.';
        }
        
        if (isset($dados['ano']) && (!is_numeric($dados['ano']) || (int)$dados['ano'] < 1900 || (int)$dados['ano'] > 2100)) {
            $erros['ano'] = 'Ano inválido.';
        }

        if (isset($dados['data_pagamento'])) {
            $d = \DateTime::createFromFormat('Y-m-d', $dados['data_pagamento']);
            if (!$d || $d->format('Y-m-d') !== $dados['data_pagamento']) {
                $erros['data_pagamento'] = 'Data de pagamento inválida.';
            }
        }
        
        if (isset($dados['quantidade_parcela']) && (!is_numeric($dados['quantidade_parcela']) || (int)$dados['quantidade_parcela'] < 1)) {
            $erros['quantidade_parcela'] = 'A quantidade de parcelas deve ser no mínimo 1.';
        }
        
        if (isset($dados['status_pagamento']) && !in_array($dados['status_pagamento'], [0, 1])) {
            $erros['status_pagamento'] = 'Status de pagamento inválido.';
        }

        return $erros;
    }

    /**
     * Constrói e executa uma consulta SQL, filtrando automaticamente por `status_registro = 1`.
     */
    public function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement
    {
        $sql = "SELECT {$coluna} FROM " . self::$tabela;
        if (!empty($join)) $sql .= " {$join}";

        $sql .= " WHERE status_registro = 1";
        if (!empty($filtro)) $sql .= " AND {$filtro}";

        if (!empty($agrupamento)) $sql .= " GROUP BY {$agrupamento}";
        if (!empty($ordem)) $sql .= " ORDER BY {$ordem}";
        if (!empty($limite)) $sql .= " LIMIT {$limite}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    /**
     * Cadastra um novo custo mensal.
     * `data_criacao` e `status_registro` são gerenciados pelo banco de dados.
     */
    public function cadastrar(\PDO $conn, array $dados): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " 
                    (status_pagamento, tipo_custo, quantidade_parcela, descricao, valor, data_pagamento, mes, ano, criado_por) 
                    VALUES (:status_pagamento, :tipo_custo, :quantidade_parcela, :descricao, :valor, :data_pagamento, :mes, :ano, 1)";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':status_pagamento' => (int)($dados['status_pagamento'] ?? 0),
            ':tipo_custo' => $dados['tipo_custo'],
            ':quantidade_parcela' => (int)($dados['quantidade_parcela'] ?? 1),
            ':descricao' => $dados['descricao'],
            ':valor' => (float)$dados['valor'],
            ':data_pagamento' => $dados['data_pagamento'],
            ':mes' => (int)$dados['mes'],
            ':ano' => (int)$dados['ano']
        ]);
    }

    /**
     * Edita um custo mensal existente.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para edição.", 400);
        }
        if (!$this->buscarPorId($conn, $id)) {
            throw new \Exception("O registro não foi encontrado.", 404);
        }

        $erros = $this->validar($conn, $dados, $id);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "UPDATE " . self::$tabela . " SET 
                    status_pagamento = :status_pagamento, tipo_custo = :tipo_custo, quantidade_parcela = :quantidade_parcela, 
                    descricao = :descricao, valor = :valor, data_pagamento = :data_pagamento, mes = :mes, ano = :ano,
                    alterado_por = 1
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':status_pagamento' => (int)($dados['status_pagamento'] ?? 0),
            ':tipo_custo' => $dados['tipo_custo'],
            ':quantidade_parcela' => (int)($dados['quantidade_parcela'] ?? 1),
            ':descricao' => $dados['descricao'],
            ':valor' => (float)$dados['valor'],
            ':data_pagamento' => $dados['data_pagamento'],
            ':mes' => (int)$dados['mes'],
            ':ano' => (int)$dados['ano'],
            ':id' => $id
        ]);
    }

    /**
     * Realiza a exclusão lógica de um custo mensal, definindo `status_registro` como 0.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }
        if (!$this->buscarPorId($conn, $id)) {
            throw new \Exception("O registro não foi encontrado.", 404);
        }

        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1 WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Lista todos os custos mensais ativos.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'id, status_pagamento, tipo_custo, quantidade_parcela, descricao, valor, data_pagamento, mes, ano';
        $stmt = $this->query($conn, coluna: $cols, filtro: $filtro, valor: $valor);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um custo mensal ativo pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, status_pagamento, tipo_custo, quantidade_parcela, descricao, valor, data_pagamento, mes, ano';
        $stmt = $this->query($conn, coluna: $cols, filtro: 'id = :id', valor: [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
