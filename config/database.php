<?php
// Autoload PSR-4 para o namespace FrangoFusca
spl_autoload_register(function ($class) {
    $prefix = 'FrangoFusca\\';
    $baseDir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use FrangoFusca\Database\Connection;

function getDb(): \PDO {
    return Connection::getInstance();
}
