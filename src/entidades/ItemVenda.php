<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;
use FrangoFusca\Entidades\Produto; // Para ajustar o estoque

class ItemVenda implements IEntidade
{
    private static string $tabela = 'item_venda';

    // Propriedades privadas
    private int $id;
    private int $status_registro;
    private int $venda_id;
    private int $produto_id;
    private int $quantidade;
    private float $preco_venda;

    // Getters
    public function getId(): int { return $this->id; }
    public function getStatusRegistro(): int { return $this->status_registro; }
    public function getVendaId(): int { return $this->venda_id; }
    public function getProdutoId(): int { return $this->produto_id; }
    public function getQuantidade(): int { return $this->quantidade; }
    public function getPrecoVenda(): float { return $this->preco_venda; }

    // Setters (para uso interno, por exemplo, na hidratação de objetos)
    public function setId(int $id): void { $this->id = $id; }
    public function setStatusRegistro(int $status_registro): void { $this->status_registro = $status_registro; }
    public function setVendaId(int $venda_id): void { $this->venda_id = $venda_id; }
    public function setProdutoId(int $produto_id): void { $this->produto_id = $produto_id; }
    public function setQuantidade(int $quantidade): void { $this->quantidade = $quantidade; }
    public function setPrecoVenda(float $preco_venda): void { $this->preco_venda = $preco_venda; }


    /**
     * Valida os dados para cadastro ou edição de Item de Venda.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param array $dados Dados a serem validados.
     * @param int|null $id ID do registro (usado na edição para ignorar o próprio registro na verificação de duplicidade).
     * @return array Retorna um array de erros. Se vazio, a validação passou.
     */
    public static function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        // Validação de Venda ID
        if (!isset($dados['venda_id']) || !is_numeric($dados['venda_id']) || (int)$dados['venda_id'] <= 0) {
            $erros['venda_id'] = 'O ID da venda é obrigatório.';
        } else {
            // Verifica se a Venda existe
            if (!Venda::buscarPorId($conn, (int)$dados['venda_id'])) {
                $erros['venda_id'] = 'Venda não encontrada.';
            }
        }

        // Validação de Produto ID
        if (!isset($dados['produto_id']) || !is_numeric($dados['produto_id']) || (int)$dados['produto_id'] <= 0) {
            $erros['produto_id'] = 'O ID do produto é obrigatório.';
        } else {
            // Verifica se o Produto existe
            if (!Produto::buscarPorId($conn, (int)$dados['produto_id'])) {
                $erros['produto_id'] = 'Produto não encontrado.';
            }
        }

        // Validação de Quantidade
        if (!isset($dados['quantidade']) || !is_numeric($dados['quantidade']) || (int)$dados['quantidade'] <= 0) {
            $erros['quantidade'] = 'A quantidade deve ser um número inteiro positivo.';
        } else {
            // Verifica disponibilidade em estoque (apenas no cadastro ou se a quantidade aumentar na edição)
            if ($id === null || (isset($dados['quantidade_antiga']) && (int)$dados['quantidade'] > (int)$dados['quantidade_antiga'])) {
                $produto = Produto::buscarPorId($conn, (int)$dados['produto_id']);
                if ($produto && (int)$dados['quantidade'] > $produto['quantidade_comprada']) {
                    $erros['quantidade'] = 'Quantidade indisponível em estoque para este produto.';
                }
            }
        }

        // Validação de Preço de Venda
        if (!isset($dados['preco_venda']) || !is_numeric($dados['preco_venda']) || (float)$dados['preco_venda'] < 0) {
            $erros['preco_venda'] = 'O preço de venda deve ser um valor numérico não negativo.';
        }

        // Validação de duplicidade: Um produto pode aparecer apenas uma vez por venda (se for para somar, a edição deve ser usada)
        if (empty($erros) && isset($dados['venda_id']) && isset($dados['produto_id'])) {
            $filtro = 'venda_id = ? AND produto_id = ? AND status_registro = 1';
            $valores = [$dados['venda_id'], $dados['produto_id']];

            if ($id !== null) {
                $filtro .= ' AND id != ?';
                $valores[] = $id;
            }
            
            $stmt = self::query($conn, 'id', '', $filtro, $valores);

            if ($stmt->fetch()) {
                $erros['duplicidade'] = 'Este produto já foi adicionado a esta venda.';
            }
        }
        
        // Validação de status_registro
        if (isset($dados['status_registro']) && !in_array((int)$dados['status_registro'], [0, 1])) {
            $erros['status_registro'] = 'Status de registro inválido. Deve ser 0 (inativo) ou 1 (ativo).';
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

    public static function cadastrar(\PDO $conn, array $dados): bool
    {
        $erros = self::validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $conn->beginTransaction();
        try {
            $sql = "INSERT INTO " . self::$tabela . " 
                        (status_registro, venda_id, produto_id, quantidade, preco_venda, criado_por, data_criacao) 
                        VALUES (?, ?, ?, ?, ?, 1, NOW())";
            
            $stmt = $conn->prepare($sql);
            $sucesso = $stmt->execute([
                (int)($dados['status_registro'] ?? 1), // Padrão ativo
                (int)$dados['venda_id'],
                (int)$dados['produto_id'],
                (int)$dados['quantidade'],
                (float)$dados['preco_venda']
            ]);

            if ($sucesso) {
                // Ajustar estoque do produto
                $produto = Produto::buscarPorId($conn, (int)$dados['produto_id']);
                if ($produto) {
                    $novaQuantidade = $produto['quantidade_comprada'] - (int)$dados['quantidade'];
                    if ($novaQuantidade < 0) {
                        throw new \Exception("Quantidade insuficiente em estoque para o produto.", 400);
                    }
                    $sqlProduto = "UPDATE " . Produto::getTabela() . " SET quantidade_comprada = ?, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
                    $stmtProduto = $conn->prepare($sqlProduto);
                    $stmtProduto->execute([$novaQuantidade, (int)$dados['produto_id']]);
                }
            }

            $conn->commit();
            return $sucesso;
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }
    }

    public static function editar(\PDO $conn, ?int $id, array $dados): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para edição.", 400);
        }

        $itemExistente = self::buscarPorId($conn, $id);
        if (!$itemExistente) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        // Adiciona a quantidade antiga para validação de estoque
        $dados['quantidade_antiga'] = $itemExistente['quantidade'];
        $erros = self::validar($conn, $dados, $id);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $conn->beginTransaction();
        try {
            $sql = "UPDATE " . self::$tabela . " SET 
                        status_registro = ?, venda_id = ?, produto_id = ?, quantidade = ?, preco_venda = ?, 
                        alterado_por = 1, data_alteracao = NOW() 
                    WHERE id = ?";
            
            $stmt = $conn->prepare($sql);
            $sucesso = $stmt->execute([
                (int)($dados['status_registro'] ?? 1),
                (int)$dados['venda_id'],
                (int)$dados['produto_id'],
                (int)$dados['quantidade'],
                (float)$dados['preco_venda'],
                $id
            ]);

            if ($sucesso) {
                // Ajustar estoque: se o produto_id mudou ou a quantidade mudou
                $quantidadeDiferenca = (int)$dados['quantidade'] - (int)$itemExistente['quantidade'];
                
                // Reverter estoque do produto antigo se o produto_id mudou
                if ((int)$dados['produto_id'] !== (int)$itemExistente['produto_id']) {
                    $produtoAntigo = Produto::buscarPorId($conn, (int)$itemExistente['produto_id']);
                    if ($produtoAntigo) {
                        $novaQuantidadeAntiga = $produtoAntigo['quantidade_comprada'] + (int)$itemExistente['quantidade'];
                        $sqlProdutoAntigo = "UPDATE " . Produto::getTabela() . " SET quantidade_comprada = ?, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
                        $stmtProdutoAntigo = $conn->prepare($sqlProdutoAntigo);
                        $stmtProdutoAntigo->execute([$novaQuantidadeAntiga, (int)$itemExistente['produto_id']]);
                    }
                    // Diminuir estoque do novo produto
                    $produtoNovo = Produto::buscarPorId($conn, (int)$dados['produto_id']);
                    if ($produtoNovo) {
                        $novaQuantidadeNova = $produtoNovo['quantidade_comprada'] - (int)$dados['quantidade'];
                        if ($novaQuantidadeNova < 0) {
                            throw new \Exception("Quantidade insuficiente em estoque para o novo produto.", 400);
                        }
                        $sqlProdutoNovo = "UPDATE " . Produto::getTabela() . " SET quantidade_comprada = ?, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
                        $stmtProdutoNovo = $conn->prepare($sqlProdutoNovo);
                        $stmtProdutoNovo->execute([$novaQuantidadeNova, (int)$dados['produto_id']]);
                    }
                } elseif ($quantidadeDiferenca !== 0) { // Se o produto não mudou, mas a quantidade sim
                    $produto = Produto::buscarPorId($conn, (int)$dados['produto_id']);
                    if ($produto) {
                        $novaQuantidade = $produto['quantidade_comprada'] - $quantidadeDiferenca;
                        if ($novaQuantidade < 0) {
                            throw new \Exception("Quantidade insuficiente em estoque para o produto atualizado.", 400);
                        }
                        $sqlProduto = "UPDATE " . Produto::getTabela() . " SET quantidade_comprada = ?, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
                        $stmtProduto = $conn->prepare($sqlProduto);
                        $stmtProduto->execute([$novaQuantidade, (int)$dados['produto_id']]);
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

    public static function deletar(\PDO $conn, ?int $id): bool
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para exclusão.", 400);
        }

        $itemExistente = self::buscarPorId($conn, $id);
        if (!$itemExistente) {
            throw new \Exception("ID #$id não encontrado.", 404);
        }

        $conn->beginTransaction();
        try {
            // Exclusão lógica
            $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $sucesso = $stmt->execute([$id]);

            if ($sucesso) {
                // Reverter a quantidade do produto para o estoque
                $produto = Produto::buscarPorId($conn, (int)$itemExistente['produto_id']);
                if ($produto) {
                    $novaQuantidade = $produto['quantidade_comprada'] + (int)$itemExistente['quantidade'];
                    $sqlProduto = "UPDATE " . Produto::getTabela() . " SET quantidade_comprada = ?, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
                    $stmtProduto = $conn->prepare($sqlProduto);
                    $stmtProduto->execute([$novaQuantidade, (int)$itemExistente['produto_id']]);
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
     * Lista todos os itens de venda ativos para uma venda específica.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int $vendaId ID da venda.
     * @return array Retorna um array de itens de venda.
     */
    public static function listarPorVenda(\PDO $conn, int $vendaId): array
    {
        if (empty($vendaId)) {
            throw new \Exception("ID da venda é obrigatório para listar itens.", 400);
        }
        $cols = 'iv.id, iv.venda_id, iv.produto_id, p.nome as produto_nome, iv.quantidade, iv.preco_venda';
        $join = 'JOIN produto p ON iv.produto_id = p.id';
        $filtro = 'iv.venda_id = ? AND iv.status_registro = 1';
        $stmt = self::query($conn, $cols, $join, $filtro, [$vendaId], 'p.nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um item de venda pelo ID.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int|null $id ID do item de venda.
     * @return array|null Retorna os dados do item de venda ou null se não encontrado/inválido.
     */
    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, status_registro, venda_id, produto_id, quantidade, preco_venda';
        $stmt = self::query($conn, $cols, '', 'id = ? AND status_registro = 1', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    // Método auxiliar para obter o nome da tabela do Produto (usado para ajustar o estoque)
    private static function getTabela(): string {
        return self::$tabela;
    }
}
