<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Receita implements IEntidade
{
    private static string $tabela = 'receita';

    // Propriedades privadas
    private int $id;
    private int $status_registro;
    private int $produto_principal_id;
    private int $produto_ingrediente_id;
    private float $quantidade_necessaria;
    private int $unidade_medida_id;

    // Getters
    public function getId(): int { return $this->id; }
    public function getStatusRegistro(): int { return $this->status_registro; }
    public function getProdutoPrincipalId(): int { return $this->produto_principal_id; }
    public function getProdutoIngredienteId(): int { return $this->produto_ingrediente_id; }
    public function getQuantidadeNecessaria(): float { return $this->quantidade_necessaria; }
    public function getUnidadeMedidaId(): int { return $this->unidade_medida_id; }

    // Setters (para uso interno, por exemplo, na hidratação de objetos)
    public function setId(int $id): void { $this->id = $id; }
    public function setStatusRegistro(int $status_registro): void { $this->status_registro = $status_registro; }
    public function setProdutoPrincipalId(int $produto_principal_id): void { $this->produto_principal_id = $produto_principal_id; }
    public function setProdutoIngredienteId(int $produto_ingrediente_id): void { $this->produto_ingrediente_id = $produto_ingrediente_id; }
    public function setQuantidadeNecessaria(float $quantidade_necessaria): void { $this->quantidade_necessaria = $quantidade_necessaria; }
    public function setUnidadeMedidaId(int $unidade_medida_id): void { $this->unidade_medida_id = $unidade_medida_id; }


    /**
     * Valida os dados para cadastro ou edição de Receita.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param array $dados Dados a serem validados.
     * @param int|null $id ID do registro (usado na edição para ignorar o próprio registro na verificação de duplicidade).
     * @return array Retorna um array de erros. Se vazio, a validação passou.
     */
    public static function validar(\PDO $conn, array $dados, ?int $id = null): array
    {
        $erros = [];

        // Validação de IDs de Produtos e Unidade de Medida
        if (!isset($dados['produto_principal_id']) || !is_numeric($dados['produto_principal_id']) || (int)$dados['produto_principal_id'] <= 0) {
            $erros['produto_principal_id'] = 'O Produto Principal é obrigatório.';
        } else {
            // Verifica se o Produto Principal existe
            if (!Produto::buscarPorId($conn, (int)$dados['produto_principal_id'])) {
                $erros['produto_principal_id'] = 'Produto Principal não encontrado.';
            }
        }

        if (!isset($dados['produto_ingrediente_id']) || !is_numeric($dados['produto_ingrediente_id']) || (int)$dados['produto_ingrediente_id'] <= 0) {
            $erros['produto_ingrediente_id'] = 'O Produto Ingrediente é obrigatório.';
        } else {
            // Verifica se o Produto Ingrediente existe
            if (!Produto::buscarPorId($conn, (int)$dados['produto_ingrediente_id'])) {
                $erros['produto_ingrediente_id'] = 'Produto Ingrediente não encontrado.';
            }
        }

        if (isset($dados['produto_principal_id']) && isset($dados['produto_ingrediente_id']) && $dados['produto_principal_id'] == $dados['produto_ingrediente_id']) {
            $erros['produtos'] = 'O Produto Principal não pode ser o mesmo que o Produto Ingrediente.';
        }

        if (!isset($dados['quantidade_necessaria']) || !is_numeric($dados['quantidade_necessaria']) || (float)$dados['quantidade_necessaria'] <= 0) {
            $erros['quantidade_necessaria'] = 'A quantidade necessária deve ser um valor numérico positivo.';
        }

        if (!isset($dados['unidade_medida_id']) || !is_numeric($dados['unidade_medida_id']) || (int)$dados['unidade_medida_id'] <= 0) {
            $erros['unidade_medida_id'] = 'A Unidade de Medida é obrigatória.';
        } else {
            // Verifica se a Unidade de Medida existe
            if (!UnidadeMedida::buscarPorId($conn, (int)$dados['unidade_medida_id'])) {
                $erros['unidade_medida_id'] = 'Unidade de Medida não encontrada.';
            }
        }

        // Validação de duplicidade: uma receita para um produto principal com um ingrediente específico
        if (empty($erros) && isset($dados['produto_principal_id']) && isset($dados['produto_ingrediente_id'])) {
            $filtro = 'produto_principal_id = ? AND produto_ingrediente_id = ?';
            $valores = [$dados['produto_principal_id'], $dados['produto_ingrediente_id']];

            if ($id !== null) {
                $filtro .= ' AND id != ?';
                $valores[] = $id;
            }
            
            $stmt = self::query($conn, 'id', '', $filtro, $valores);

            if ($stmt->fetch()) {
                $erros['duplicidade'] = 'Esta combinação de Produto Principal e Ingrediente já existe em uma receita.';
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
                    (status_registro, produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id, criado_por, data_criacao) 
                    VALUES (?, ?, ?, ?, ?, ?, 1, NOW())";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)($dados['status_registro'] ?? 1), // Padrão ativo
            (int)$dados['produto_principal_id'],
            (int)$dados['produto_ingrediente_id'],
            (float)$dados['quantidade_necessaria'],
            (int)$dados['unidade_medida_id']
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
                    status_registro = ?, produto_principal_id = ?, produto_ingrediente_id = ?, 
                    quantidade_necessaria = ?, unidade_medida_id = ?, 
                    alterado_por = 1, data_alteracao = NOW() 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)($dados['status_registro'] ?? 1),
            (int)$dados['produto_principal_id'],
            (int)$dados['produto_ingrediente_id'],
            (float)$dados['quantidade_necessaria'],
            (int)$dados['unidade_medida_id'],
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
     * Lista todas as receitas ativas.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @return array Retorna um array de receitas.
     */
    public static function listar(\PDO $conn): array
    {
        $cols = 'r.id, r.status_registro, pp.nome as produto_principal_nome, pi.nome as produto_ingrediente_nome, r.quantidade_necessaria, um.sigla as unidade_medida_sigla';
        $join = 'JOIN produto pp ON r.produto_principal_id = pp.id JOIN produto pi ON r.produto_ingrediente_id = pi.id JOIN unidade_medida um ON r.unidade_medida_id = um.id';
        $stmt = self::query($conn, $cols, $join, 'r.status_registro = 1', [], 'pp.nome ASC, pi.nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca uma receita pelo ID.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int|null $id ID da receita.
     * @return array|null Retorna os dados da receita ou null se não encontrada/inválida.
     */
    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, status_registro, produto_principal_id, produto_ingrediente_id, quantidade_necessaria, unidade_medida_id';
        $stmt = self::query($conn, $cols, '', 'id = ? AND status_registro = 1', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
