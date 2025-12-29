<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Venda implements IEntidade
{
    private static string $tabela = 'venda';

    /**
     * Valida os dados para cadastro ou edição de uma venda.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro para edição (não utilizado neste método).
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];
        $cliente = new Cliente();
        $usuario = new Usuario();
        $metodoPagamento = new MetodoPagamento();
        $ambienteVenda = new AmbienteVenda();

        if (empty($dados['cliente_id']) || !$cliente->buscarPorId($conn, (int)$dados['cliente_id'])) {
            $erros['cliente_id'] = 'Cliente inválido ou não encontrado.';
        }
        if (empty($dados['vendedor_id']) || !$usuario->buscarPorId($conn, (int)$dados['vendedor_id'])) {
            $erros['vendedor_id'] = 'Vendedor inválido ou não encontrado.';
        }
        if (empty($dados['metodo_pagamento_id']) || !$metodoPagamento->buscarPorId($conn, (int)$dados['metodo_pagamento_id'])) {
            $erros['metodo_pagamento_id'] = 'Método de Pagamento inválido ou não encontrado.';
        }
        if (empty($dados['ambiente_venda_id']) || !$ambienteVenda->buscarPorId($conn, (int)$dados['ambiente_venda_id'])) {
            $erros['ambiente_venda_id'] = 'Ambiente de Venda inválido ou não encontrado.';
        }

        if (empty(trim($dados['data_venda']))) {
            $erros['data_venda'] = 'A data da venda é obrigatória.';
        } else {
            $d = \DateTime::createFromFormat('Y-m-d H:i:s', $dados['data_venda']);
            if (!$d || $d->format('Y-m-d H:i:s') !== $dados['data_venda']) {
                $erros['data_venda'] = 'Formato de data inválido (esperado YYYY-MM-DD HH:MM:SS).';
            }
        }

        if (!isset($dados['valor_total']) || !is_numeric($dados['valor_total']) || (float)$dados['valor_total'] < 0) {
            $erros['valor_total'] = 'O valor total deve ser um valor numérico não negativo.';
        }

        return $erros;
    }

    /**
     * Constrói e executa uma consulta SQL genérica na tabela.
     * Nota: Esta tabela não usa exclusão lógica (status_registro).
     */
    public function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement
    {
        $sql = "SELECT {$coluna} FROM " . self::$tabela . " v";
        if (!empty($join)) $sql .= " {$join}";
        if (!empty($filtro)) $sql .= " WHERE {$filtro}";
        if (!empty($agrupamento)) $sql .= " GROUP BY {$agrupamento}";
        if (!empty($ordem)) $sql .= " ORDER BY {$ordem}";
        if (!empty($limite)) $sql .= " LIMIT {$limite}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    /**
     * Cadastra uma nova venda.
     * `data_criacao` é gerenciado pelo banco de dados.
     *
     * @return int O ID da venda inserida.
     */
    public function cadastrar(\PDO $conn, array $dados, int $idUsuario): int
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "INSERT INTO " . self::$tabela . " 
                    (cliente_id, vendedor_id, data_venda, valor_total, metodo_pagamento_id, ambiente_venda_id, criado_por) 
                    VALUES (:cliente_id, :vendedor_id, :data_venda, :valor_total, :metodo_pagamento_id, :ambiente_venda_id, :criado_por)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':cliente_id' => (int)$dados['cliente_id'],
            ':vendedor_id' => (int)$dados['vendedor_id'],
            ':data_venda' => $dados['data_venda'],
            ':valor_total' => (float)$dados['valor_total'],
            ':metodo_pagamento_id' => (int)$dados['metodo_pagamento_id'],
            ':ambiente_venda_id' => (int)$dados['ambiente_venda_id'],
            ':criado_por' => $idUsuario
        ]);

        return (int)$conn->lastInsertId();
    }

    /**
     * Edita uma venda existente.
     * `data_alteracao` é gerenciado pelo banco de dados.
     */
    public function editar(\PDO $conn, ?int $idRegistro, array $dados, int $idUsuario): bool
    {
        if (empty($idRegistro)) throw new \Exception("ID é obrigatório para edição.", 400);
        if (!$this->buscarPorId($conn, $idRegistro)) throw new \Exception("O registro não foi encontrado.", 404);

        $erros = $this->validar($conn, $dados, $idRegistro);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "UPDATE " . self::$tabela . " SET 
                    cliente_id = :cliente_id, vendedor_id = :vendedor_id, data_venda = :data_venda, 
                    valor_total = :valor_total, metodo_pagamento_id = :metodo_pagamento_id, 
                    ambiente_venda_id = :ambiente_venda_id, alterado_por = :alterado_por
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':cliente_id' => (int)$dados['cliente_id'],
            ':vendedor_id' => (int)$dados['vendedor_id'],
            ':data_venda' => $dados['data_venda'],
            ':valor_total' => (float)$dados['valor_total'],
            ':metodo_pagamento_id' => (int)$dados['metodo_pagamento_id'],
            ':ambiente_venda_id' => (int)$dados['ambiente_venda_id'],
            ':alterado_por' => $idUsuario,
            ':id' => $idRegistro
        ]);
    }

    /**
     * Realiza a exclusão física de uma venda.
     * CUIDADO: Esta ação é irreversível e pode deixar itens de venda órfãos se não forem tratados.
     */
    public function deletar(\PDO $conn, ?int $idRegistro, int $idUsuario): bool
    {
        if (empty($idRegistro)) throw new \Exception("ID é obrigatório para exclusão.", 400);
        if (!$this->buscarPorId($conn, $idRegistro)) throw new \Exception("O registro não foi encontrado.", 404);

        $sql = "DELETE FROM " . self::$tabela . " WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':id' => $idRegistro]);
    }

    /**
     * Lista todas as vendas com informações de tabelas relacionadas.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'v.id, c.nome AS cliente_nome, u.nome AS vendedor_nome, v.data_venda, v.valor_total, mp.nome AS metodo_pagamento_nome, av.nome AS ambiente_venda_nome';
        $join = 'JOIN cliente c ON v.cliente_id = c.id 
                 JOIN usuario u ON v.vendedor_id = u.id 
                 JOIN metodo_pagamento mp ON v.metodo_pagamento_id = mp.id 
                 JOIN ambiente_venda av ON v.ambiente_venda_id = av.id';
        
        $stmt = $this->query($conn, $cols, $join, $filtro, $valor, 'v.data_venda DESC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma venda pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) return null;

        $cols = 'id, cliente_id, vendedor_id, data_venda, valor_total, metodo_pagamento_id, ambiente_venda_id';
        $stmt = $this->query($conn, $cols, '', 'v.id = :id', [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
