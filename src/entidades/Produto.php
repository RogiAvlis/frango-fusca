<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Produto implements IEntidade
{
    private static string $tabela = 'produto';

    // Propriedades privadas
    private int $id;
    private int $status_registro;
    private string $nome;
    private ?string $descricao;
    private float $preco_custo;
    private float $preco_venda;
    private int $quantidade_comprada;
    private int $unidade_medida_id;
    private int $fornecedor_id;

    // Getters
    public function getId(): int { return $this->id; }
    public function getStatusRegistro(): int { return $this->status_registro; }
    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function getPrecoCusto(): float { return $this->preco_custo; }
    public function getPrecoVenda(): float { return $this->preco_venda; }
    public function getQuantidadeComprada(): int { return $this->quantidade_comprada; }
    public function getUnidadeMedidaId(): int { return $this->unidade_medida_id; }
    public function getFornecedorId(): int { return $this->fornecedor_id; }

    // Setters (para uso interno, por exemplo, na hidratação de objetos)
    public function setId(int $id): void { $this->id = $id; }
    public function setStatusRegistro(int $status_registro): void { $this->status_registro = $status_registro; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setDescricao(?string $descricao): void { $this->descricao = $descricao; }
    public function setPrecoCusto(float $preco_custo): void { $this->preco_custo = $preco_custo; }
    public function setPrecoVenda(float $preco_venda): void { $this->preco_venda = $preco_venda; }
    public function setQuantidadeComprada(int $quantidade_comprada): void { $this->quantidade_comprada = $quantidade_comprada; }
    public function setUnidadeMedidaId(int $unidade_medida_id): void { $this->unidade_medida_id = $unidade_medida_id; }
    public function setFornecedorId(int $fornecedor_id): void { $this->fornecedor_id = $fornecedor_id; }


    /**
     * Valida os dados para cadastro ou edição de Produto.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param array $dados Dados a serem validados.
     * @param int|null $id ID do registro (usado na edição para ignorar o próprio registro na verificação de duplicidade).
     * @return array Retorna um array de erros. Se vazio, a validação passou.
     */
    public static function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        // Validação de Nome obrigatório
        if (empty(trim($dados['nome']))) {
            $erros['nome'] = 'O nome é obrigatório.';
        }

        // Validação de Preços
        if (!isset($dados['preco_custo']) || !is_numeric($dados['preco_custo']) || (float)$dados['preco_custo'] < 0) {
            $erros['preco_custo'] = 'O preço de custo deve ser um valor numérico não negativo.';
        }
        if (!isset($dados['preco_venda']) || !is_numeric($dados['preco_venda']) || (float)$dados['preco_venda'] < 0) {
            $erros['preco_venda'] = 'O preço de venda deve ser um valor numérico não negativo.';
        }
        if (isset($dados['preco_custo']) && isset($dados['preco_venda']) && (float)$dados['preco_custo'] > (float)$dados['preco_venda']) {
            $erros['preco_venda'] = 'O preço de venda não pode ser menor que o preço de custo.';
        }

        // Validação de Quantidade Comprada
        if (!isset($dados['quantidade_comprada']) || !is_numeric($dados['quantidade_comprada']) || (int)$dados['quantidade_comprada'] < 0) {
            $erros['quantidade_comprada'] = 'A quantidade comprada deve ser um número inteiro não negativo.';
        }

        // Validação de IDs de Unidade de Medida e Fornecedor
        if (!isset($dados['unidade_medida_id']) || !is_numeric($dados['unidade_medida_id']) || (int)$dados['unidade_medida_id'] <= 0) {
            $erros['unidade_medida_id'] = 'A Unidade de Medida é obrigatória.';
        } else {
            // Verifica se a Unidade de Medida existe
            if (!UnidadeMedida::buscarPorId($conn, (int)$dados['unidade_medida_id'])) {
                $erros['unidade_medida_id'] = 'Unidade de Medida não encontrada.';
            }
        }
        if (!isset($dados['fornecedor_id']) || !is_numeric($dados['fornecedor_id']) || (int)$dados['fornecedor_id'] <= 0) {
            $erros['fornecedor_id'] = 'O Fornecedor é obrigatório.';
        } else {
            // Verifica se o Fornecedor existe
            if (!Fornecedor::buscarPorId($conn, (int)$dados['fornecedor_id'])) {
                $erros['fornecedor_id'] = 'Fornecedor não encontrado.';
            }
        }

        // Validação de duplicidade para 'nome'
        if (empty($erros) && !empty(trim($dados['nome']))) {
            $filtro = 'nome = ?';
            $valores = [$dados['nome']];

            if ($id !== null) {
                $filtro .= ' AND id != ?';
                $valores[] = $id;
            }
            
            $stmt = self::query($conn, 'id', '', $filtro, $valores);

            if ($stmt->fetch()) {
                $erros['nome'] = 'Já existe um produto com este nome.';
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

        $sql = "INSERT INTO " . self::$tabela . " 
                    (status_registro, nome, descricao, preco_custo, preco_venda, quantidade_comprada, unidade_medida_id, fornecedor_id, criado_por, data_criacao) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)($dados['status_registro'] ?? 1), // Padrão ativo
            $dados['nome'],
            empty($dados['descricao']) ? null : $dados['descricao'],
            (float)$dados['preco_custo'],
            (float)$dados['preco_venda'],
            (int)$dados['quantidade_comprada'],
            (int)$dados['unidade_medida_id'],
            (int)$dados['fornecedor_id']
        ]);
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
                    status_registro = ?, nome = ?, descricao = ?, preco_custo = ?, preco_venda = ?, 
                    quantidade_comprada = ?, unidade_medida_id = ?, fornecedor_id = ?, 
                    alterado_por = 1, data_alteracao = NOW() 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)($dados['status_registro'] ?? 1),
            $dados['nome'],
            empty($dados['descricao']) ? null : $dados['descricao'],
            (float)$dados['preco_custo'],
            (float)$dados['preco_venda'],
            (int)$dados['quantidade_comprada'],
            (int)$dados['unidade_medida_id'],
            (int)$dados['fornecedor_id'],
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

        // Exclusão lógica
        $sql = "UPDATE " . self::$tabela . " SET status_registro = 0, alterado_por = 1, data_alteracao = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Lista todos os produtos ativos.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @return array Retorna um array de produtos.
     */
    public static function listar(\PDO $conn): array
    {
        $cols = 'p.id, p.status_registro, p.nome, p.descricao, p.preco_custo, p.preco_venda, p.quantidade_comprada, um.sigla as unidade_medida_sigla, f.nome as fornecedor_nome';
        $join = 'LEFT JOIN unidade_medida um ON p.unidade_medida_id = um.id LEFT JOIN fornecedor f ON p.fornecedor_id = f.id';
        $stmt = self::query($conn, $cols, $join, 'p.status_registro = 1', [], 'p.nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um produto pelo ID.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int|null $id ID do produto.
     * @return array|null Retorna os dados do produto ou null se não encontrado/inválido.
     */
    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, status_registro, nome, descricao, preco_custo, preco_venda, quantidade_comprada, unidade_medida_id, fornecedor_id';
        $stmt = self::query($conn, $cols, '', 'id = ? AND status_registro = 1', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
