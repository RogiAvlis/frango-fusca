<?php
namespace FrangoFusca\Models;

class Usuario extends AbstractModel {
    protected static string $table = 'usuario';
    protected static bool $useEstabelecimento = true;

    public static function listarProfissionais(int $estabelecimentoId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT id, estabelecimento_id, nome, email, perfil, status_registro, data_criacao 
            FROM usuario 
            WHERE estabelecimento_id = :estabelecimento_id AND status_registro = 1 
            ORDER BY id DESC
        ");
        $stmt->execute([':estabelecimento_id' => $estabelecimentoId]);
        return $stmt->fetchAll();
    }

    public static function criarConvite(array $dados, int $estabelecimentoId, int $usuarioId): array {
        $db = static::getDb();
        $email = strtolower(trim($dados['email'] ?? ''));
        $perfil = $dados['perfil'] ?? 'vendedor';

        if (empty($email)) {
            return ['success' => false, 'message' => 'O e-mail para envio do convite é obrigatório.'];
        }

        // Verifica se usuário já existe
        $stmtCheck = $db->prepare("SELECT id FROM usuario WHERE email = :email LIMIT 1");
        $stmtCheck->execute([':email' => $email]);
        if ($stmtCheck->fetch()) {
            return ['success' => false, 'message' => 'Este e-mail já pertence a um usuário cadastrado no sistema.'];
        }

        // Gera código randômico de convite (ex: FF-8A9X2B)
        $codigoConvite = 'FF-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        $stmt = $db->prepare("
            INSERT INTO convite_usuario (estabelecimento_id, email, codigo_convite, perfil, criado_por) 
            VALUES (:estabelecimento_id, :email, :codigo_convite, :perfil, :criado_por)
        ");
        $success = $stmt->execute([
            ':estabelecimento_id' => $estabelecimentoId,
            ':email' => $email,
            ':codigo_convite' => $codigoConvite,
            ':perfil' => $perfil,
            ':criado_por' => $usuarioId
        ]);

        if ($success) {
            return [
                'success' => true,
                'message' => 'Convite gerado com sucesso!',
                'codigo' => $codigoConvite,
                'link' => BASE_URL . '/aceitar_convite.php?codigo=' . $codigoConvite
            ];
        }

        return ['success' => false, 'message' => 'Erro ao gerar convite.'];
    }

    public static function listarConvites(int $estabelecimentoId): array {
        $db = static::getDb();
        $stmt = $db->prepare("
            SELECT * FROM convite_usuario 
            WHERE estabelecimento_id = :estabelecimento_id AND status_convite = 'pendente' 
            ORDER BY id DESC
        ");
        $stmt->execute([':estabelecimento_id' => $estabelecimentoId]);
        return $stmt->fetchAll();
    }
}
