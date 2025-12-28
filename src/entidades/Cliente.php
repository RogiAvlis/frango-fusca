<?php

namespace FrangoFusca\Entidades;

use FrangoFusca\Core\IEntidade;

class Cliente implements IEntidade
{
    private static string $tabela = 'cliente';

    // Propriedades privadas
    private int $id;
    private int $status_registro;
    private string $nome;
    private ?string $telefone;

    // Getters
    public function getId(): int { return $this->id; }
    public function getStatusRegistro(): int { return $this->status_registro; }
    public function getNome(): string { return $this->nome; }
    public function getTelefone(): ?string { return $this->telefone; }

    // Setters (para uso interno, por exemplo, na hidratação de objetos)
    public function setId(int $id): void { $this->id = $id; }
    public function setStatusRegistro(int $status_registro): void { $this->status_registro = $status_registro; }
    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setTelefone(?string $telefone): void { $this->telefone = $telefone; }


    /**
     * Valida os dados para cadastro ou edição de Cliente.
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

        // Validação de duplicidade para 'nome'
        if (!empty(trim($dados['nome']))) {
            $filtro = 'nome = ?';
            $valores = [$dados['nome']];

            if ($id !== null) {
                $filtro .= ' AND id != ?';
                $valores[] = $id;
            }
            
            $stmt = self::query($conn, 'id', '', $filtro, $valores);

            if ($stmt->fetch()) {
                $erros['nome'] = 'Já existe um cliente com este nome.';
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
                    (status_registro, nome, telefone, criado_por, data_criacao) 
                    VALUES (?, ?, ?, 1, NOW())";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)($dados['status_registro'] ?? 1), // Padrão ativo
            $dados['nome'],
            empty($dados['telefone']) ? null : $dados['telefone']
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
                    status_registro = ?, nome = ?, telefone = ?, 
                    alterado_por = 1, data_alteracao = NOW() 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([
            (int)($dados['status_registro'] ?? 1),
            $dados['nome'],
            empty($dados['telefone']) ? null : $dados['telefone'],
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
     * Lista todos os clientes ativos.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @return array Retorna um array de clientes.
     */
    public static function listar(\PDO $conn): array
    {
        $cols = 'id, status_registro, nome, telefone';
        $stmt = self::query($conn, $cols, '', 'status_registro = 1', [], 'nome ASC');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca um cliente pelo ID.
     *
     * @param \PDO $conn Conexão com o banco de dados.
     * @param int|null $id ID do cliente.
     * @return array|null Retorna os dados do cliente ou null se não encontrado/inválido.
     */
    public static function buscarPorId(\PDO $conn, ?int $id): ?array
    {
        if (empty($id)) {
            throw new \Exception("ID é obrigatório para busca.", 400);
        }
        $cols = 'id, status_registro, nome, telefone';
        $stmt = self::query($conn, $cols, '', 'id = ? AND status_registro = 1', [$id]);
        $resultado = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $resultado ?: null;
    }
}
