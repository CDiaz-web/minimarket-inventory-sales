<?php

use Controllers\AuthController;
use Controllers\CategoriasController;
use Controllers\ClientesController;
use Controllers\CorrelativosController;
use Controllers\DashboardController;
use Controllers\DevolucionesController;
use Controllers\EligeTiendaController;
use Controllers\EmpresaController;
use Controllers\FactorCambioController;
use Controllers\InventariosController;
use Controllers\ListasController;
use Controllers\OrdenVentaController;
use Controllers\PerfilesController;
use Controllers\ProductosController;
use Controllers\TiendaProductosController;
use Controllers\TiendasController;
use Controllers\TipoClientesController;
use Controllers\TipoPagoController;
use Controllers\TiposMovimientosController;
use Controllers\UnidadesController;
use Controllers\UsuariosController;
use Controllers\UsuariosTiendasController;



//========== AUTENTICACION
//===== LOGIN
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

//=====  REGISTRO - CREAR CUENTA
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

//=====  REGISTRO - FORMULARIO OLVIDE PSW
$router->get('/olvide', [AuthController::class, 'olvide']);
$router->post('/olvide', [AuthController::class, 'olvide']);

//=====  FORMULARIO NUEVO PASSWORD
$router->get('/reestablecer', [AuthController::class, 'reestablecer']);
$router->post('/reestablecer', [AuthController::class, 'reestablecer']);

//=====  FORMULARIO CONFIORMAR CUENTA
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);




//========== GESTION
//===== DASHBOARD
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

//===== LOGISTICA
//== INVENTARIO
$router->get('/admin/gestion/logistica/inventario',[InventariosController::class,'index']);
$router->post('/admin/gestion/logistica/inventario/crear', [InventariosController::class, 'crear']);
$router->get('/admin/gestion/logistica/inventario/crear', [InventariosController::class, 'crear']);
$router->post('/admin/gestion/logistica/inventario/editar', [InventariosController::class, 'editar']);
$router->get('/admin/gestion/logistica/inventario/editar', [InventariosController::class, 'editar']);
$router->post('/admin/gestion/logistica/inventario/anular', [InventariosController::class, 'anular']);
$router->get('/admin/gestion/logistica/inventario/imprimir', [InventariosController::class, 'imprimir']);
//===== VENTAS
//== ORDEN VENTA
$router->get('/admin/gestion/ventas/orden',[OrdenVentaController::class,'index']);
$router->get('/admin/gestion/ventas/orden/validarTipoCambio',[OrdenVentaController::class,'validarTipoCambio']);
$router->post('/admin/gestion/ventas/orden/validarTipoCambio',[OrdenVentaController::class,'validarTipoCambio']);
$router->post('/admin/gestion/ventas/orden/generar',[OrdenVentaController::class,'generar']);
//===== CAMBIO DE TIENDA
$router->get('/tiendas', [EligeTiendaController::class, 'index']);
$router->post('/tiendas', [EligeTiendaController::class, 'index']);
//========== MANTENIMIENTOS
//===== PRODUCTOS
//== CATEGORIAS
$router->get('/admin/mantenimiento/productos/categorias', [CategoriasController::class, 'index']);
$router->post('/admin/mantenimiento/productos/categorias/crear', [CategoriasController::class, 'crear']);
$router->get('/admin/mantenimiento/productos/categorias/crear', [CategoriasController::class, 'crear']);
$router->post('/admin/mantenimiento/productos/categorias/editar', [CategoriasController::class, 'editar']);
$router->get('/admin/mantenimiento/productos/categorias/editar', [CategoriasController::class, 'editar']);
$router->post('/admin/mantenimiento/productos/categorias/eliminar', [CategoriasController::class, 'eliminar']);
$router->post('/admin/mantenimiento/productos/categorias/cargar', [CategoriasController::class, 'cargar']);
$router->get('/admin/mantenimiento/productos/categorias/cargar', [CategoriasController::class, 'cargar']);

//== PRODUCTOS
$router->get('/admin/mantenimiento/productos/productos',[ProductosController::class,'index']);
$router->post('/admin/mantenimiento/productos/productos/crear', [ProductosController::class, 'crear']);
$router->get('/admin/mantenimiento/productos/productos/crear', [ProductosController::class, 'crear']);
$router->post('/admin/mantenimiento/productos/productos/editar', [ProductosController::class, 'editar']);
$router->get('/admin/mantenimiento/productos/productos/editar', [ProductosController::class, 'editar']);
$router->post('/admin/mantenimiento/productos/productos/eliminar', [ProductosController::class, 'eliminar']);
$router->post('/admin/mantenimiento/productos/productos/cargar', [ProductosController::class, 'cargar']);
$router->get('/admin/mantenimiento/productos/productos/cargar', [ProductosController::class, 'cargar']);

//== PRODUCTOS TIENDA
$router->get('/admin/mantenimiento/productos/tiendaproductos',[TiendaProductosController::class,'index']);
$router->post('/admin/mantenimiento/productos/tiendaproductos/editar', [TiendaProductosController::class, 'editar']);

//===== LISTA DE PRECIOS
$router->get('/admin/mantenimiento/listas',[ListasController::class,'index']);
$router->post('/admin/mantenimiento/listas/crear', [ListasController::class, 'crear']);
$router->get('/admin/mantenimiento/listas/crear', [ListasController::class, 'crear']);
$router->post('/admin/mantenimiento/listas/editar', [ListasController::class, 'editar']);
$router->get('/admin/mantenimiento/listas/editar', [ListasController::class, 'editar']);
$router->post('/admin/mantenimiento/listas/eliminar', [ListasController::class, 'eliminar']);

//===== CLIENTES
//== CLASIFICACION
$router->get('/admin/mantenimiento/clientes/clasificacion',[TipoClientesController::class,'index']);
$router->post('/admin/mantenimiento/clientes/clasificacion/crear', [TipoClientesController::class, 'crear']);
$router->get('/admin/mantenimiento/clientes/clasificacion/crear', [TipoClientesController::class, 'crear']);
$router->post('/admin/mantenimiento/clientes/clasificacion/editar', [TipoClientesController::class, 'editar']);
$router->get('/admin/mantenimiento/clientes/clasificacion/editar', [TipoClientesController::class, 'editar']);
$router->post('/admin/mantenimiento/clientes/clasificacion/eliminar', [TipoClientesController::class, 'eliminar']);
$router->post('/admin/mantenimiento/clientes/clasificacion/cargar', [TipoClientesController::class, 'cargar']);
$router->get('/admin/mantenimiento/clientes/clasificacion/cargar', [TipoClientesController::class, 'cargar']);

//== CLIENTES
$router->get('/admin/mantenimiento/clientes/clientes',[ClientesController::class,'index']);
$router->post('/admin/mantenimiento/clientes/clientes/crear', [ClientesController::class, 'crear']);
$router->get('/admin/mantenimiento/clientes/clientes/crear', [ClientesController::class, 'crear']);
$router->post('/admin/mantenimiento/clientes/clientes/editar', [ClientesController::class, 'editar']);
$router->get('/admin/mantenimiento/clientes/clientes/editar', [ClientesController::class, 'editar']);
$router->post('/admin/mantenimiento/clientes/clientes/eliminar', [ClientesController::class, 'eliminar']);
$router->post('/admin/mantenimiento/clientes/clientes/cargar', [ClientesController::class, 'cargar']);
$router->get('/admin/mantenimiento/clientes/clientes/cargar', [ClientesController::class, 'cargar']);
$router->get('/admin/mantenimiento/clientes/clientes/traerDocumento', [ClientesController::class, 'traerDocumento']);

//===== TIPO DE CAMBIO
$router->get('/admin/mantenimiento/factor',[FactorCambioController::class,'index']);
$router->post('/admin/mantenimiento/factor/crear', [FactorCambioController::class, 'crear']);
$router->get('/admin/mantenimiento/factor/crear', [FactorCambioController::class, 'crear']);
$router->post('/admin/mantenimiento/factor/editar', [FactorCambioController::class, 'editar']);
$router->get('/admin/mantenimiento/factor/editar', [FactorCambioController::class, 'editar']);
$router->post('/admin/mantenimiento/factor/eliminar', [FactorCambioController::class, 'eliminar']);
$router->get('/admin/mantenimiento/factor/traerSUNAT', [FactorCambioController::class, 'traerSUNAT']);

//========== CONFIGURACION
//===== EMPRESA
$router->post('/admin/configuracion/empresa/editar', [EmpresaController::class, 'editar']);
$router->get('/admin/configuracion/empresa/editar', [EmpresaController::class, 'editar']);

//===== TIENDA
$router->get('/admin/configuracion/tiendas', [TiendasController::class, 'index']);
$router->post('/admin/configuracion/tiendas/crear', [TiendasController::class, 'crear']);
$router->get('/admin/configuracion/tiendas/crear', [TiendasController::class, 'crear']);
$router->post('/admin/configuracion/tiendas/editar', [TiendasController::class, 'editar']);
$router->get('/admin/configuracion/tiendas/editar', [TiendasController::class, 'editar']);
$router->post('/admin/configuracion/tiendas/eliminar', [TiendasController::class, 'eliminar']);

//===== UNIDAD DE MEDIDA
$router->get('/admin/configuracion/unidad', [UnidadesController::class, 'index']);
$router->post('/admin/configuracion/unidad/crear', [UnidadesController::class, 'crear']);
$router->get('/admin/configuracion/unidad/crear', [UnidadesController::class, 'crear']);
$router->post('/admin/configuracion/unidad/editar', [UnidadesController::class, 'editar']);
$router->get('/admin/configuracion/unidad/editar', [UnidadesController::class, 'editar']);
$router->post('/admin/configuracion/unidad/eliminar', [UnidadesController::class, 'eliminar']);

//===== TIPO MOVIMIENTO
$router->get('/admin/configuracion/tipo_movimiento', [TiposMovimientosController::class, 'index']);
$router->post('/admin/configuracion/tipo_movimiento/crear', [TiposMovimientosController::class, 'crear']);
$router->get('/admin/configuracion/tipo_movimiento/crear', [TiposMovimientosController::class, 'crear']);
$router->post('/admin/configuracion/tipo_movimiento/editar', [TiposMovimientosController::class, 'editar']);
$router->get('/admin/configuracion/tipo_movimiento/editar', [TiposMovimientosController::class, 'editar']);
$router->post('/admin/configuracion/tipo_movimiento/eliminar', [TiposMovimientosController::class, 'eliminar']);

//===== CORRELATIVOS
$router->get('/admin/configuracion/correlativos', [CorrelativosController::class, 'index']);
$router->post('/admin/configuracion/correlativos/crear', [CorrelativosController::class, 'crear']);
$router->get('/admin/configuracion/correlativos/crear', [CorrelativosController::class, 'crear']);
$router->post('/admin/configuracion/correlativos/eliminar', [CorrelativosController::class, 'eliminar']);

//===== MOTIVO DEVOLUCION
$router->get('/admin/configuracion/devolucion', [DevolucionesController::class, 'index']);
$router->post('/admin/configuracion/devolucion/crear', [DevolucionesController::class, 'crear']);
$router->get('/admin/configuracion/devolucion/crear', [DevolucionesController::class, 'crear']);
$router->post('/admin/configuracion/devolucion/editar', [DevolucionesController::class, 'editar']);
$router->get('/admin/configuracion/devolucion/editar', [DevolucionesController::class, 'editar']);
$router->post('/admin/configuracion/devolucion/eliminar', [DevolucionesController::class, 'eliminar']);

//===== TIPOS DE PAGO
$router->get('/admin/configuracion/tipopago', [TipoPagoController::class, 'index']);
$router->post('/admin/configuracion/tipopago/crear', [TipoPagoController::class, 'crear']);
$router->get('/admin/configuracion/tipopago/crear', [TipoPagoController::class, 'crear']);
$router->post('/admin/configuracion/tipopago/editar', [TipoPagoController::class, 'editar']);
$router->get('/admin/configuracion/tipopago/editar', [TipoPagoController::class, 'editar']);
$router->post('/admin/configuracion/tipopago/eliminar', [TipoPagoController::class, 'eliminar']);
//========== SEGURIDAD
//===== PERFILES
$router->get('/admin/seguridad/perfiles', [PerfilesController::class, 'index']);
$router->post('/admin/seguridad/perfiles/crear', [PerfilesController::class, 'crear']);
$router->get('/admin/seguridad/perfiles/crear', [PerfilesController::class, 'crear']);
$router->post('/admin/seguridad/perfiles/editar', [PerfilesController::class, 'editar']);
$router->get('/admin/seguridad/perfiles/editar', [PerfilesController::class, 'editar']);
$router->post('/admin/seguridad/perfiles/eliminar', [PerfilesController::class, 'eliminar']);
$router->post('/admin/seguridad/perfiles/opciones', [PerfilesController::class, 'opciones']);
$router->get('/admin/seguridad/perfiles/opciones', [PerfilesController::class, 'opciones']);

//===== USUARIOS
$router->get('/admin/seguridad/usuarios', [UsuariosController::class, 'index']);
$router->post('/admin/seguridad/usuarios/crear', [UsuariosController::class, 'crear']);
$router->get('/admin/seguridad/usuarios/crear', [UsuariosController::class, 'crear']);
$router->post('/admin/seguridad/usuarios/editar', [UsuariosController::class, 'editar']);
$router->get('/admin/seguridad/usuarios/editar', [UsuariosController::class, 'editar']);
$router->post('/admin/seguridad/usuarios/eliminar', [UsuariosController::class, 'eliminar']);

//== USUARIOS TIENDA
$router->get('/admin/seguridad/usuarios/tiendas', [UsuariosController::class, 'tiendas']);
$router->post('/admin/seguridad/usuarios/tiendas', [UsuariosController::class, 'tiendas']);
$router->get('/admin/seguridad/usuarios/tiendas/crear', [UsuariosTiendasController::class, 'crear']);
$router->post('/admin/seguridad/usuarios/tiendas/crear', [UsuariosTiendasController::class, 'crear']);
$router->post('/admin/seguridad/usuarios/tiendas/eliminar', [UsuariosTiendasController::class, 'eliminar']);













