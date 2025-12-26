<?php

namespace FrangoFusca\Db;

class Conexao {
    private static ?\PDO $instancia = null;

    private function __construct() {}

    public static function obterConexao(): \PDO {
        if (self::$instancia === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            try {
                self::$instancia = new \PDO($dsn, DB_USER, DB_PW, $options);
            } catch (\PDOException $e) {
                // Em um ambiente de produção, você pode querer logar o erro em vez de exibi-lo.
                // Por agora, vamos exibir para depuração.
                die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
            }
        }
        return self::$instancia;
    }
}
