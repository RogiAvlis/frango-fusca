<?php
namespace FrangoFusca\Models;

use FrangoFusca\Database\Connection;

class Estabelecimento {
    public static function buscarPorId(int $id): ?array {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT * FROM estabelecimento WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function salvar(array $dados, int $id, int $usuarioId): bool {
        $db = Connection::getInstance();
        $stmt = $db->prepare("
            UPDATE estabelecimento 
            SET nome = :nome, cnpj = :cnpj, endereco = :endereco, telefone_contato = :telefone_contato, 
                nome_responsavel = :nome_responsavel, telefone_responsavel = :telefone_responsavel, email = :email, 
                alterado_por = :alterado_por 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nome' => trim($dados['nome']),
            ':cnpj' => trim($dados['cnpj'] ?? ''),
            ':endereco' => trim($dados['endereco'] ?? ''),
            ':telefone_contato' => trim($dados['telefone_contato'] ?? ''),
            ':nome_responsavel' => trim($dados['nome_responsavel'] ?? ''),
            ':telefone_responsavel' => trim($dados['telefone_responsavel'] ?? ''),
            ':email' => trim($dados['email'] ?? ''),
            ':alterado_por' => $usuarioId,
            ':id' => $id
        ]);
    }
}
