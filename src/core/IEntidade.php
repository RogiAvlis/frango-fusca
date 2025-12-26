<?php

interface IEntidade {
    public function obterId(): ?int;
    public function definirId(int $id): void;
    public static function cadastrar(array $dados): bool;
    public static function editar(int $id, array $dados): bool;
    public static function deletar(int $id): bool;
    public static function listar(?array $filtros = null): array;
    public static function buscarPorId(int $id): ?array;
}
