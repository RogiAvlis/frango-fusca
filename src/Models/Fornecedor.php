<?php
namespace FrangoFusca\Models;

class Fornecedor extends AbstractModel {
    protected static string $table = 'fornecedor';
    protected static bool $useEstabelecimento = true;

    public static function salvar(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE fornecedor 
                SET nome = :nome, cnpj_cpf = :cnpj_cpf, email = :email, telefone = :telefone, endereco = :endereco, alterado_por = :alterado_por 
                WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
            ");
            return $stmt->execute([
                ':nome' => trim($dados['nome']),
                ':cnpj_cpf' => trim($dados['cnpj_cpf'] ?? ''),
                ':email' => trim($dados['email'] ?? ''),
                ':telefone' => trim($dados['telefone'] ?? ''),
                ':endereco' => trim($dados['endereco'] ?? ''),
                ':alterado_por' => $usuarioId,
                ':id' => $id,
                ':estabelecimento_id' => $estabelecimentoId
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO fornecedor (estabelecimento_id, nome, cnpj_cpf, email, telefone, endereco, criado_por) 
                VALUES (:estabelecimento_id, :nome, :cnpj_cpf, :email, :telefone, :endereco, :criado_por)
            ");
            return $stmt->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':nome' => trim($dados['nome']),
                ':cnpj_cpf' => trim($dados['cnpj_cpf'] ?? ''),
                ':email' => trim($dados['email'] ?? ''),
                ':telefone' => trim($dados['telefone'] ?? ''),
                ':endereco' => trim($dados['endereco'] ?? ''),
                ':criado_por' => $usuarioId
            ]);
        }
    }
}
