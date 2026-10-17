<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

use FrangoFusca\Controllers\AuthController;

AuthController::logout();
header('Location: ' . BASE_URL . '/login.php');
exit;
