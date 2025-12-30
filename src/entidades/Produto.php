<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Produto implements IEntidade
{
    private static string $tabela = 'produto';

    /**
     * Retorna o nome da tabela da entidade.
     *
     * @return string
     */
    public function getTabela(): string
    {
        return self::$tabela;
    }

    /**
     * Valida os dados para cadastro ou edição de um produto.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param array $dados Os dados a serem validados.
     * @param int|null $id O ID do registro para edição.
     * @return array Um array com os erros de validação.
     */
    public function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];
        $unidadeMedida = new UnidadeMedida();
        $fornecedor = new Fornecedor();

        if (empty(trim($dados['nome']))) $erros['nome'] = 'O nome é obrigatório.';

        if (!isset($dados['preco_custo']) || !is_numeric($dados['preco_custo']) || (float)$dados['preco_custo'] < 0) {
            $erros['preco_custo'] = 'O preço de custo deve ser um valor numérico não negativo.';
        }
        if (!isset($dados['preco_venda']) || !is_numeric($dados['preco_venda']) || (float)$dados['preco_venda'] < 0) {
            $erros['preco_venda'] = 'O preço de venda deve ser um valor numérico não negativo.';
        }
        if (isset($dados['preco_custo'], $dados['preco_venda']) && (float)$dados['preco_custo'] > (float)$dados['preco_venda']) {
            $erros['preco_venda'] = 'O preço de venda não pode ser menor que o preço de custo.';
        }

        if (!isset($dados['quantidade_comprada']) || !is_numeric($dados['quantidade_comprada']) || (int)$dados['quantidade_comprada'] < 0) {
            $erros['quantidade_comprada'] = 'A quantidade deve ser um número inteiro não negativo.';
        }

        if (empty($dados['unidade_medida_id'])) {
            $erros['unidade_medida_id'] = 'A Unidade de Medida é obrigatória.';
        } elseif (!$unidadeMedida->buscarPorId($conn, (int)$dados['unidade_medida_id'])) {
            $erros['unidade_medida_id'] = 'Unidade de Medida não encontrada.';
        }

        if (empty($dados['fornecedor_id'])) {
            $erros['fornecedor_id'] = 'O Fornecedor é obrigatório.';
        } elseif (!$fornecedor->buscarPorId($conn, (int)$dados['fornecedor_id'])) {
            $erros['fornecedor_id'] = 'Fornecedor não encontrado.';
        }

        if (empty($erros) && !empty(trim($dados['nome']))) {
            $filtro = 'nome = :nome';
            $valores = [':nome' => $dados['nome']];

            if ($id !== null) {
                $filtro .= ' AND id != :id';
                $valores[':id'] = $id;
            }

            if ($this->query($conn, coluna: 'id', filtro: $filtro, valor: $valores)->fetch()) {
                $erros['nome'] = 'Já existe um produto com este nome.';
            }
        }

        return $erros;
    }

    /**
     * Constrói e executa uma consulta SQL, filtrando automaticamente por `status_registro = 1`.
     */
    public function query(\PDO $conn, string $coluna = '*', ?string $join = '', ?string $filtro = '', ?array $valor = [], ?string $ordem = '', ?string $agrupamento = '', ?string $limit = ''): \PDOStatement
    {
        $sql = "SELECT {$coluna} FROM " . self::$tabela . " p";
        if (!empty($join)) $sql .= " {$join}";

        $sql .= " WHERE p.status_registro = 1";
        if (!empty($filtro)) $sql .= " AND {$filtro}";

        if (!empty($agrupamento)) $sql .= " GROUP BY {$agrupamento}";
        if (!empty($ordem)) $sql .= " ORDER BY {$ordem}";
        if (!empty($limite)) $sql .= " LIMIT {$limite}";

        $stmt = $conn->prepare($sql);
        $stmt->execute($valor);

        return $stmt;
    }

    /**
     * Cadastra um novo produto.
     * `data_criacao` e `status_registro` são gerenciados pelo banco de dados.
     */
    public function cadastrar(\PDO $conn, array $dados, int $idUsuario): bool
    {
        $erros = $this->validar($conn, $dados);
        if (!empty($erros)) {
            throw new \Exception(implode("\n", $erros), 400);
        }

        $sql = "INSERT INTO " . self::$tabela . " 
                    (nome, descricao, preco_custo, preco_venda, quantidade_comprada, unidade_medida_id, fornecedor_id, criado_por) 
                    VALUES (:nome, :descricao, :preco_custo, :preco_venda, :quantidade_comprada, :unidade_medida_id, :fornecedor_id, :criado_por)";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => empty($dados['descricao']) ? null : $dados['descricao'],
            ':preco_custo' => (float)$dados['preco_custo'],
            ':preco_venda' => (float)$dados['preco_venda'],
            ':quantidade_comprada' => (int)$dados['quantidade_comprada'],
            ':unidade_medida_id' => (int)$dados['unidade_medida_id'],
            ':fornecedor_id' => (int)$dados['fornecedor_id'],
            ':criado_por' => $idUsuario
        ]);
    }

    /**
     * Edita um produto existente.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function editar(\PDO $conn, ?int $idRegistro, array $dados, int $idUsuario): bool
    {
        if (empty($idRegistro)) throw new \Exception("ID é obrigatório para edição.", 400);
        if (!$this->buscarPorId($conn, $idRegistro)) throw new \Exception("O registro não foi encontrado.", 404);

        $erros = $this->validar($conn, $dados, $idRegistro);
        if (!empty($erros)) throw new \Exception(implode("\n", $erros), 400);

        $sql = "UPDATE " . self::$tabela . " SET 
                    nome = :nome, descricao = :descricao, preco_custo = :preco_custo, preco_venda = :preco_venda, 
                    quantidade_comprada = :quantidade_comprada, unidade_medida_id = :unidade_medida_id, fornecedor_id = :fornecedor_id, 
                    alterado_por = :alterado_por
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':descricao' => empty($dados['descricao']) ? null : $dados['descricao'],
            ':preco_custo' => (float)$dados['preco_custo'],
            ':preco_venda' => (float)$dados['preco_venda'],
            ':quantidade_comprada' => (int)$dados['quantidade_comprada'],
            ':unidade_medida_id' => (int)$dados['unidade_medida_id'],
            ':fornecedor_id' => (int)$dados['fornecedor_id'],
            ':alterado_por' => $idUsuario,
            ':id' => $idRegistro
        ]);
    }

    /**
     * Realiza a exclusão lógica de um produto.
     * `data_alteracao` é gerenciado automaticamente pelo banco de dados.
     */
    public function deletar(\PDO $conn, ?int $idRegistro, int $idUsuario): bool
    {
        if (empty($idRegistro)) throw new \Exception("ID é obrigatório para exclusão.", 400);
        if (!$this->buscarPorId($conn, $idRegistro)) throw new \Exception("O registro não foi encontrado.", 404);

        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = :alterado_por WHERE id = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            ':id' => $idRegistro,
            ':alterado_por' => $idUsuario
        ]);
    }

    /**
     * Lista todos os produtos ativos com informações de tabelas relacionadas.
     */
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array
    {
        $cols = 'p.id, p.nome, p.descricao, p.preco_custo, p.preco_venda, p.quantidade_comprada, um.sigla as unidade_medida_sigla, f.nome as fornecedor_nome';
        $join = 'LEFT JOIN unidade_medida um ON p.unidade_medida_id = um.id LEFT JOIN fornecedor f ON p.fornecedor_id = f.id';
        $stmt = $this->query($conn, coluna: $cols, join: $join, filtro: $filtro, valor: $valor, ordem: 'p.nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um produto ativo pelo seu ID.
     */
    public function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) return null;

        $cols = 'id, nome, descricao, preco_custo, preco_venda, quantidade_comprada, unidade_medida_id, fornecedor_id';
        $stmt = $this->query($conn, coluna: $cols, filtro: 'p.id = :id', valor: [':id' => $id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }

    /**
     * Ajusta a quantidade em estoque de um produto.
     *
     * @param \PDO $conn A conexão com o banco de dados.
     * @param int $produtoId O ID do produto a ser ajustado.
     * @param int $diferenca A quantidade a ser adicionada (positiva) ou removida (negativa).
     * @return bool Retorna true em caso de sucesso.
     * @throws \Exception Se o produto não for encontrado ou o estoque ficar negativo.
     */
    public function ajustarEstoque(\PDO $conn, int $produtoId, int $diferenca, int $idUsuario): bool
    {
        $produto = $this->buscarPorId($conn, $produtoId);
        if (!$produto) {
            throw new \Exception("Produto com ID $produtoId não encontrado para ajuste de estoque.", 404);
        }

        $novaQuantidade = $produto['quantidade_comprada'] + $diferenca;
        if ($novaQuantidade < 0) {
            throw new \Exception("Estoque insuficiente para o produto '{$produto['nome']}'.", 400);
        }

        $sql = "UPDATE " . self::$tabela . " SET quantidade_comprada = :quantidade, alterado_por = :alterado_por WHERE id = :id";
        $stmt = $conn->prepare($sql);
        
        return $stmt->execute([
            ':quantidade' => $novaQuantidade,
            ':id' => $produtoId,
            ':alterado_por' => $idUsuario
        ]);
    }
}
