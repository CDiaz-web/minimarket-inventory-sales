<?php

use Controllers\APIClientes;
use Controllers\APIListas;
use Controllers\APIMonedas;
use Controllers\APIOpciones;
use Controllers\APIProductos;
use Controllers\APITipoPago;
use Controllers\APIVentasMes;
use Controllers\APIproductosTiendaLista;
use Controllers\APITotalProductosTienda;

// =====================
// API Opciones
// =====================
$router->post('/api/guardaropciones',[APIOpciones::class,'guardaropciones']);
$router->get('/api/productos',[APIProductos::class,'productos']);
$router->get('/api/tipopago',[APITipoPago::class,'tipopago']);
$router->get('/api/monedas',[APIMonedas::class,'monedas']);
$router->get('/api/clientes',[APIClientes::class,'clientes']);
$router->get('/api/listas',[APIListas::class,'listas']);
$router->get('/api/ventasmes',[APIVentasMes::class,'ventasmes']);
$router->get('/api/totalproductostienda',[APITotalProductosTienda::class,'productos']);
$router->get('/api/productosporlista',[APIproductosTiendaLista::class,'productosporlista']);