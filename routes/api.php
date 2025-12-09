<?php

use Controllers\APIClientes;
use Controllers\APIMonedas;
use Controllers\APIOpciones;
use Controllers\APIProductos;
use Controllers\APITipoPago;

// =====================
// API Opciones
// =====================
// $router->get('/api/guardaropciones',[APIOpciones::class,'guardaropciones']);
$router->post('/api/guardaropciones',[APIOpciones::class,'guardaropciones']);
$router->get('/api/productos',[APIProductos::class,'productos']);
$router->get('/api/tipopago',[APITipoPago::class,'tipopago']);
$router->get('/api/monedas',[APIMonedas::class,'monedas']);
$router->get('/api/clientes',[APIClientes::class,'clientes']);