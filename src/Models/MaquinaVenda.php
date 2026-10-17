<?php
namespace FrangoFusca\Models;

class MaquinaVenda extends AbstractModel {
    protected static string $table = 'maquina_venda';
    protected static bool $useEstabelecimento = true;

    public static function salvar(array $dados, int $estabelecimentoId, int $usuarioId): bool {
        $db = static::getDb();
        $id = isset($dados['id']) && $dados['id'] ? (int)$dados['id'] : null;

        $tipoTaxa = $dados['tipo_taxa'] ?? 'percentual';
        $taxa = !empty($dados['taxa']) ? (float)str_replace(',', '.', $dados['taxa']) : 0.00;
        $mensalidadeValor = !empty($dados['mensalidade_valor']) ? (float)str_replace(',', '.', $dados['mensalidade_valor']) : 0.00;

        if ($id) {
            $stmt = $db->prepare("
                UPDATE maquina_venda 
                SET nome = :nome, descricao = :descricao, tipo_taxa = :tipo_taxa, taxa = :taxa, mensalidade_valor = :mensalidade_valor, alterado_por = :alterado_por 
                WHERE id = :id AND estabelecimento_id = :estabelecimento_id AND status_registro = 1
            ");
            return $stmt->execute([
                ':nome' => trim($dados['nome']),
                ':descricao' => trim($dados['descricao'] ?? ''),
                ':tipo_taxa' => $tipoTaxa,
                ':taxa' => $taxa,
                ':mensalidade_valor' => $mensalidadeValor,
                ':alterado_por' => $usuarioId,
                ':id' => $id,
                ':estabelecimento_id' => $estabelecimentoId
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO maquina_venda (estabelecimento_id, nome, descricao, tipo_taxa, taxa, mensalidade_valor, criado_por) 
                VALUES (:estabelecimento_id, :nome, :descricao, :tipo_taxa, :taxa, :mensalidade_valor, :criado_por)
            ");
            return $stmt->execute([
                ':estabelecimento_id' => $estabelecimentoId,
                ':nome' => trim($dados['nome']),
                ':descricao' => trim($dados['descricao'] ?? ''),
                ':tipo_taxa' => $tipoTaxa,
                ':taxa' => $taxa,
                ':mensalidade_valor' => $mensalidadeValor,
                ':criado_por' => $usuarioId
            ]);
        }
    }
}
