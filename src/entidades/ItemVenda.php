<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class ItemVenda implements IEntidade
{
    private static string $tabela = 'item_venda';

    /**
     * Valida os dados para cadastro ou edição de um item de venda.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro para edição.
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];
        $venda = new Venda();
        $produto = new Produto();

        if (empty($dados['venda_id']) || !is_numeric($dados['venda_id'])) {
            $erros['venda_id'] = 'O ID da venda é obrigatório.';
        } elseif (!$venda->buscarPorId($conn, (int)$dados['venda_id'])) {
            $erros['venda_id'] = 'Venda não encontrada.';
        }

        if (empty($dados['produto_id']) || !is_numeric($dados['produto_id'])) {
            $erros['produto_id'] = 'O ID do produto é obrigatório.';
        } elseif (!$produto->buscarPorId($conn, (int)$dados['produto_id'])) {
            $erros['produto_id'] = 'Produto não encontrado.';
        }

        if (!isset($dados['quantidade']) || !is_numeric($dados['quantidade']) || (int)$dados['quantidade'] <= 0) {
            $erros['quantidade'] = 'A quantidade deve ser um número inteiro positivo.';
        }

        if (!isset($dados['preco_venda']) || !is_numeric($dados['preco_venda']) || (float)$dados['preco_venda'] < 0) {
            $erros['preco_venda'] = 'O preço de venda deve ser um valor numérico não negativo.';
        }

        if (empty($erros)) {
            $filtro = 'venda_id = :venda_id AND produto_id = :produto_id';
            $valores = [':venda_id' => $dados['venda_id'], ':produto_id' => $dados['produto_id']];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }

            if ($this->query($conn, coluna: 'id', filtro: $filtro, valor: $valores)->fetch()) {
                $erros['duplicidade'] = 'Este produto já foi adicionado a esta venda.';
            }
        }
        
        return $erros;
    }

    /**
     * Constrói e executa uma consulta SQL, filtrando automaticamente por `status_registro = 1`.
     */
    public function query(\PDO $conn, string $coluna = '*', ?string $join = '', ?string $filtro = '', ?array $valor = [], ?string $ordem = '', ?string $agrupamento = '', ?string $limit = ''): \PDOStatement
    {
        $sql = "SELECT {$coluna} FROM " . self::$tabela . " iv"; // Alias 'iv'
        if (!empty($join)) $sql .= " {$join}";
        
        $sql .= " WHERE iv.status_registro = 1";
        if (!empty($filtro)) $sql .= " AND {$filtro}";

        if (!empty($agrupamento)) $sql .= " GROUP BY {$agrupamento}";
        if (!empty($ordem)) $sql .= " ORDER BY {$ordem}";
        if (!empty($limit)) $sql .= " LIMIT {$limit}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    /**
     * Cadastra um novo item de venda e ajusta o estoque do produto.
     * `data_criacao` e `status_registro` são gerenciados pelo banco de dados.
     */
    public function cadastrar(\PDO $conn, array $dados): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $conn->beginTransaction();
        try {
            $sql = "INSERT INTO " . self::$tabela . " 
                        (venda_id, produto_id, quantidade, preco_venda, criado_por) 
                        VALUES (:venda_id, :produto_id, :quantidade, :preco_venda, 1)";
            
            $stmt = $conn->prepare($sql);
            $sucesso = $stmt->execute([
                ':venda_id' => (int)$dados['venda_id'],
                ':produto_id' => (int)$dados['produto_id'],
                ':quantidade' => (int)$dados['quantidade'],
                ':preco_venda' => (float)$dados['preco_venda']
            ]);

            if ($sucesso) {
                $produto = new Produto();
                $produto->ajustarEstoque($conn, (int)$dados['produto_id'], -(int)$dados['quantidade']);
            }

            $conn->commit();
            return $sucesso;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    /**
     * Edita um item de venda existente e ajusta o estoque dos produtos envolvidos.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para edição.", 400);
        
        $itemExistente = $this->buscarPorId($conn, $id);
        if (!$itemExistente) throw new \Exception("O registro não foi encontrado.", 404);

        $erros = $this->validar($conn, $dados, $id);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $conn->beginTransaction();
        try {
            $sql = "UPDATE " . self::$tabela . " SET 
                        venda_id = :venda_id, produto_id = :produto_id, quantidade = :quantidade, 
                        preco_venda = :preco_venda, alterado_por = 1
                    WHERE id = :id";
            
            $stmt = $conn->prepare($sql);
            $sucesso = $stmt->execute([
                ':venda_id' => (int)$dados['venda_id'],
                ':produto_id' => (int)$dados['produto_id'],
                ':quantidade' => (int)$dados['quantidade'],
                ':preco_venda' => (float)$dados['preco_venda'],
                ':id' => $id
            ]);

            if ($sucesso) {
                $produto = new Produto();
                $produtoOriginalId = (int)$itemExistente['produto_id'];
                $produtoNovoId = (int)$dados['produto_id'];
                $quantidadeOriginal = (int)$itemExistente['quantidade'];
                $quantidadeNova = (int)$dados['quantidade'];

                if ($produtoOriginalId !== $produtoNovoId) {
                    $produto->ajustarEstoque($conn, $produtoOriginalId, $quantidadeOriginal); // Devolve ao estoque
                    $produto->ajustarEstoque($conn, $produtoNovoId, -$quantidadeNova);       // Retira do novo estoque
                } else {
                    $diferenca = $quantidadeNova - $quantidadeOriginal;
                    if ($diferenca !== 0) {
                        $produto->ajustarEstoque($conn, $produtoOriginalId, -$diferenca);
                    }
                }
            }

            $conn->commit();
            return $sucesso;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    /**
     * Realiza a exclusão lógica de um item de venda e reverte o estoque do produto.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para exclusão.", 400);

        $itemExistente = $this->buscarPorId($conn, $id);
        if (!$itemExistente) throw new \Exception("O registro não foi encontrado.", 404);

        $conn->beginTransaction();
        try {
            $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1 WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $sucesso = $stmt->execute([':id' => $id]);

            if ($sucesso) {
                $produto = new Produto();
                $produto->ajustarEstoque($conn, (int)$itemExistente['produto_id'], (int)$itemExistente['quantidade']);
            }

            $conn->commit();
            return $sucesso;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    /**
     * Lista todos os itens de venda ativos para uma venda específica.
     */
    public function listarPorVenda(\PDO $conn, int $vendaId): array
    {
        if (empty($vendaId)) {
            throw new \Exception("ID da venda é obrigatório para listar itens.", 400);
        }
        $cols = 'iv.id, iv.venda_id, iv.produto_id, p.nome as produto_nome, iv.quantidade, iv.preco_venda';
        $join = 'JOIN produto p ON iv.produto_id = p.id';
        $filtro = 'iv.venda_id = :venda_id';
        $stmt = $this->query($conn, coluna: $cols, join: $join, filtro: $filtro, valor: [':venda_id' => $vendaId], ordem: 'p.nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Lista todos os itens de venda ativos.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'iv.id, iv.venda_id, iv.produto_id, p.nome as produto_nome, iv.quantidade, iv.preco_venda';
        $join = 'JOIN produto p ON iv.produto_id = p.id';
        $stmt = $this->query($conn, coluna: $cols, join: $join, filtro: $filtro, valor: $valor, ordem: 'iv.id ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um item de venda ativo pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) throw new \Exception("ID é obrigatório para busca.", 400);

        $cols = 'id, venda_id, produto_id, quantidade, preco_venda';
        $stmt = $this->query($conn, coluna: $cols, filtro: 'iv.id = :id', valor: [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
