<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;

$router = new Router();

// Rutas Web
require __DIR__ . '/../routes/web.php';

// Rutas API
require __DIR__ . '/../routes/api.php';

$router->comprobarRutas();