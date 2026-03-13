<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;

$router = new Router();

define('ROOT_PATH', dirname(__DIR__));


// Rutas Web
require __DIR__ . '/../routes/web.php';

// Rutas API
require __DIR__ . '/../routes/api.php';

$router->comprobarRutas();