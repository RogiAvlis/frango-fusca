<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Venda implements IEntidade
{
    private static string $tabela = 'venda';

    // Propriedades privadas
    private int $id;
    private int $cliente_id;
    private int $vendedor_id;
    private string $data_venda;
    private float $valor_total;
    private int $metodo_pagamento_id;
    private int $ambiente_venda_id;

    // Getters
    public function getId(): int { return $this->id; }
    public function getClienteId(): int { return $this->cliente_id; }
    public function getVendedorId(): int { return $this->vendedor_id; }
    public function getDataVenda(): string { return $this->data_venda; }
    public function getValorTotal(): float { return $this->valor_total; }
    public function getMetodoPagamentoId(): int { return $this->metodo_pagamento_id; }
    public function getAmbienteVendaId(): int { return $this->ambiente_venda_id; }

    // Setters (para uso interno, por exemplo, na hidratação de objetos)
    public function setId(int $id): void { $this->id = $id; }
    public function setClienteId(int $cliente_id): void { $this->cliente_id = $cliente_id; }
    public function setVendedorId(int $vendedor_id): void { $this->vendedor_id = $vendedor_id; }
    public function setDataVenda(string $data_venda): void { $this->data_venda = $data_venda; }
    public function setValorTotal(float $valor_total): void { $this->valor_total = $valor_total; }
    public function setMetodoPagamentoId(int $metodo_pagamento_id): void { $this->metodo_pagamento_id = $metodo_pagamento_id; }
    public function setAmbienteVendaId(int $ambiente_venda_id): void { $this->ambiente_venda_id = $ambiente_venda_id; }


    /**
     * Valida os dados para cadastro ou edição de Venda.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param array $dados Dados a serem validados.
     * @param int|null $id ID do registro (usado na edição para ignorar o próprio registro na verificação de duplicidade).
     * @return array Retorna um array de erros. Se vazio, a validação passou.
     */
    public static function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        // Validação de IDs
        if (!isset($dados['cliente_id']) || !is_numeric($dados['cliente_id']) || (int)$dados['cliente_id'] <= 0) {
            $erros['cliente_id'] = 'O Cliente é obrigatório.';
        } else {
            if (!Cliente::buscarPorId($conn, (int)$dados['cliente_id'])) {
                $erros['cliente_id'] = 'Cliente não encontrado.';
            }
        }
        if (!isset($dados['vendedor_id']) || !is_numeric($dados['vendedor_id']) || (int)$dados['vendedor_id'] <= 0) {
            $erros['vendedor_id'] = 'O Vendedor é obrigatório.';
        } else {
            if (!Usuario::buscarPorId($conn, (int)$dados['vendedor_id'])) {
                $erros['vendedor_id'] = 'Vendedor não encontrado.';
            }
        }
        if (!isset($dados['metodo_pagamento_id']) || !is_numeric($dados['metodo_pagamento_id']) || (int)$dados['metodo_pagamento_id'] <= 0) {
            $erros['metodo_pagamento_id'] = 'O Método de Pagamento é obrigatório.';
        } else {
            if (!MetodoPagamento::buscarPorId($conn, (int)$dados['metodo_pagamento_id'])) {
                $erros['metodo_pagamento_id'] = 'Método de Pagamento não encontrado.';
            }
        }
        if (!isset($dados['ambiente_venda_id']) || !is_numeric($dados['ambiente_venda_id']) || (int)$dados['ambiente_venda_id'] <= 0) {
            $erros['ambiente_venda_id'] = 'O Ambiente de Venda é obrigatório.';
        } else {
            if (!AmbienteVenda::buscarPorId($conn, (int)$dados['ambiente_venda_id'])) {
                $erros['ambiente_venda_id'] = 'Ambiente de Venda não encontrado.';
            }
        }

        // Validação de Data de Venda
        if (empty(trim($dados['data_venda']))) {
            $erros['data_venda'] = 'A data da venda é obrigatória.';
        } else {
            $d = \DateTime::createFromFormat('Y-m-d H:i:s', $dados['data_venda']);
            if (!$d || $d->format('Y-m-d H:i:s') !== $dados['data_venda']) {
                $erros['data_venda'] = 'Formato de data e hora da venda inválido (esperado YYYY-MM-DD HH:MM:SS).';
            }
        }

        // Validação de Valor Total
        if (!isset($dados['valor_total']) || !is_numeric($dados['valor_total']) || (float)$dados['valor_total'] < 0) {
            $erros['valor_total'] = 'O valor total deve ser um valor numérico não negativo.';
        }

        return $erros;
    }

    public static function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement
    {
        $sql = "SELECT {$coluna} FROM " . self::$tabela;
        if (!empty($join)) $sql .= " {$join}";
        if (!empty($filtro)) $sql .= " WHERE {$filtro}";
        if (!empty($agrupamento)) $sql .= " GROUP BY {$agrupamento}";
        if (!empty($ordem)) $sql .= " ORDER BY {$ordem}";
        if (!empty($limite)) $sql .= " LIMIT {$limite}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    public static function cadastrar(\PDO $conn, array $dados): int // Retorna o ID da venda inserida
    {
        $erros = self::validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " 
                    (cliente_id, vendedor_id, data_venda, valor_total, metodo_pagamento_id, ambiente_venda_id, criado_por, data_criacao) 
                    VALUES (?, ?, ?, ?, ?, ?, 1, NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            (int)$dados['cliente_id'],
            (int)$dados['vendedor_id'],
            $dados['data_venda'],
            (float)$dados['valor_total'],
            (int)$dados['metodo_pagamento_id'],
            (int)$dados['ambiente_venda_id']
        ]);

        return (int)$conn->lastInsertId();
    }

    public static function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para edição.", 400);
        }

        if (!self::buscarPorId($conn, $id)) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        $erros = self::validar($conn, $dados, $id);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "UPDATE " . self::$tabela . " SET 
                    cliente_id = ?, vendedor_id = ?, data_venda = ?, valor_total = ?, 
                    metodo_pagamento_id = ?, ambiente_venda_id = ?, 
                    alterado_por = 1, data_alteracao = NOW() 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)$dados['cliente_id'],
            (int)$dados['vendedor_id'],
            $dados['data_venda'],
            (float)$dados['valor_total'],
            (int)$dados['metodo_pagamento_id'],
            (int)$dados['ambiente_venda_id'],
            $id
        ]);
    }

    public static function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }

        if (!self::buscarPorId($conn, $id)) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        // Exclusão física (Conforme o tarefas.md. Atenção: isso pode ter implicações)
        $sql = "DELETE FROM " . self::$tabela . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Lista todas as vendas.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @return array Retorna um array de vendas.
     */
    public static function listar(\PDO $conn): array
    {
        $cols = 'v.id, c.nome AS cliente_nome, u.nome AS vendedor_nome, v.data_venda, v.valor_total, mp.nome AS metodo_pagamento_nome, av.nome AS ambiente_venda_nome';
        $join = 'JOIN cliente c ON v.cliente_id = c.id JOIN usuario u ON v.vendedor_id = u.id JOIN metodo_pagamento mp ON v.metodo_pagamento_id = mp.id JOIN ambiente_venda av ON v.ambiente_venda_id = av.id';
        $stmt = self::query($conn, $cols, $join, '', [], 'v.data_venda DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma venda pelo ID.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int|null $id ID da venda.
     * @return array|null Retorna os dados da venda ou null se não encontrada/inválida.
     */
    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, cliente_id, vendedor_id, data_venda, valor_total, metodo_pagamento_id, ambiente_venda_id';
        $stmt = self::query($conn, $cols, '', 'id = ?', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
