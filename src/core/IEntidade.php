<?php

namespace FrangoFusca\Core;

interface IEntidade {
    public function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement;
    public function cadastrar(\PDO $conn, array $dados): bool;
    public function editar(\PDO $conn, ?int $id, array $dados): bool;
    public function deletar(\PDO $conn, ?int $id): bool;
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array;
    public function buscarPorId(\PDO $conn, ?int $id): ?array;
}
