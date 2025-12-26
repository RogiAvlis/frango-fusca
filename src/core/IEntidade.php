<?php

namespace FrangoFusca\Core;

interface IEntidade {
    public static function query(\PDO $conn, string $coluna = '*', string $join = '', string $filtro = '', array $valor = [], string $ordem = '', string $agrupamento = '', string $limit = ''): \PDOStatement;
    public static function cadastrar(\PDO $conn, array $dados): bool;
    public static function editar(\PDO $conn, ?int $id, array $dados): bool;
    public static function deletar(\PDO $conn, ?int $id): bool;
    public static function listar(\PDO $conn, ?array $filtros = null): array;
    public static function buscarPorId(\PDO $conn, ?int $id): ?array;
}
