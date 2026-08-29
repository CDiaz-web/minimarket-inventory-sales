<?php

use Controllers\APIClientes;
use Controllers\APIProveedores;
use Controllers\APIListas;
use Controllers\APIMonedas;
use Controllers\APIOpciones;
use Controllers\APIProductos;
use Controllers\APITipoPago;

use Controllers\APIproductosTiendaLista;
use Controllers\APIObtenerOrdenCompra;
use Controllers\APIProductosCompra;
use Controllers\APITotalProductosTienda;
use Controllers\APIProductosTienda;
use Controllers\APIEstados;


use Controllers\APIDashboardDetalleCards;
use Controllers\APIDashboardGrafico;
use Controllers\APIOrdenCompraRecepcion;
// =====================
// API Opciones
// =====================
$router->post('/api/guardaropciones',[APIOpciones::class,'guardaropciones']);
$router->get('/api/productos',[APIProductos::class,'productos']);
$router->get('/api/tipopago',[APITipoPago::class,'tipopago']);
$router->get('/api/monedas',[APIMonedas::class,'monedas']);
$router->get('/api/clientes',[APIClientes::class,'clientes']);
$router->get('/api/proveedores',[APIProveedores::class,'proveedores']);
$router->get('/api/productosporcompra',[APIProductosCompra::class,'productosporcompra']);

$router->get('/api/listas',[APIListas::class,'listas']);

$router->get('/api/totalproductostienda',[APITotalProductosTienda::class,'productos']);

$router->get('/api/productosporlista',[APIproductosTiendaLista::class,'productosporlista']);
$router->get('/api/productosportienda',[APIProductosTienda::class,'productosportienda']);
$router->post('/api/estados',[APIEstados::class,'estados']);

$router->get('/api/listaventasdeldia',[APIDashboardDetalleCards::class,'listaventasdeldia']);
$router->get('/api/listaventasdelmes',[APIDashboardDetalleCards::class,'listaventasdelmes']);
$router->get('/api/listaproductostienda',[APIDashboardDetalleCards::class,'listaproductostienda']);
$router->get('/api/listapocostock',[APIDashboardDetalleCards::class,'listapocostock']);
$router->get('/api/listacomprasmes',[APIDashboardDetalleCards::class,'listacomprasmes']);

$router->get('/api/dashboardgrafico',[APIDashboardGrafico::class,'dashboardgrafico']);

$router->get('/api/compras/ordenes-por-recibir',[APIOrdenCompraRecepcion::class, 'listarPorRecibir']);

$router->get('/api/compras/obtenerRecepcion',[APIObtenerOrdenCompra::class, 'obtenerRecepcion']);