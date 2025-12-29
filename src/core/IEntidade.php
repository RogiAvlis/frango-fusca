<?php

namespace FrangoFusca\Core;

interface IEntidade {
    public function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement;
    public function cadastrar(\PDO $conn, array $dados, int $idUsuario): int|bool;
    public function editar(\PDO $conn, ?int $idRegistro, array $dados, int $idUsuario): bool;
    public function deletar(\PDO $conn, ?int $idRegistro, int $idUsuario): bool;
    public function listar(\PDO $conn, ?string $filtro = null, ?array $valor = null): array;
    public function buscarPorId(\PDO $conn, ?int $id): ?array;
}
