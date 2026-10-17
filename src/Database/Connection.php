<?php
namespace FrangoFusca\Database;

use PDO;
use PDOException;

class Connection {
    private static ?PDO $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $host = 'localhost';
            $port = '3306'; // MySQL padrão
            $dbname = 'frango_fusca';
            $username = 'frango-fusca';
            $password = 'kP9!mX2#vQ8$tZ5*';
            $charset = 'utf8mb4';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
            ];

            try {
                self::$instance = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados MySQL: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
