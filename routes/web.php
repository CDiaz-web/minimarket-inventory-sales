<?php

use Controllers\AuthController;
use Controllers\CategoriasController;
use Controllers\ClientesController;
use Controllers\ProveedoresController;
use Controllers\CorrelativosController;
use Controllers\DashboardCarsController;
use Controllers\DevolucionesController;
use Controllers\EligeTiendaController;
use Controllers\EmpresaController;
use Controllers\FactorCambioController;
use Controllers\InventariosController;
use Controllers\ListasController;
use Controllers\ListaProductosController;
use Controllers\OrdenVentaController;
use Controllers\OrdenCompraController;
use Controllers\GestionOCController;
use Controllers\MovimientoController;
use Controllers\SeriesDocumentoController;

use Controllers\ReportesController;
use Controllers\GestionOrdenController;
use Controllers\RecepcionOCController;
use Controllers\PerfilesController;
use Controllers\ProductosController;
use Controllers\TiendaProductosController;
use Controllers\TiendasController;
use Controllers\TipoClientesController;
use Controllers\TipoPagoController;
use Controllers\TiposMovimientosController;
use Controllers\UnidadesController;
use Controllers\UsuariosController;

//========== AUTENTICACION
//===== RUTA PRINCIPAL
$router->get('/', function(){
    header('Location: /login');
    exit;
});

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
$router->get('/admin/dashboard', [DashboardCarsController::class, 'index']);

//===== COMPRAS
//== ORDEN COMPRA
$router->get('/admin/gestion/compras/orden',[OrdenCompraController::class,'index']);
$router->post('/admin/gestion/compras/orden/validarTipoCambio',[OrdenCompraController::class,'validarTipoCambio']);
$router->post('/admin/gestion/compras/orden/generar',[OrdenCompraController::class,'generar']);
$router->post('/admin/gestion/compras/orden/editar',[OrdenCompraController::class,'editar']);
$router->get('/admin/gestion/compras/orden/imprimir', [OrdenCompraController::class, 'imprimir']);

//== GESTION ORDEN COMPRA
$router->get('/admin/gestion/compras/gestion',[GestionOCController::class,'index']);
$router->post('/admin/gestion/compras/gestion/cambiarestado',[GestionOCController::class,'cambiarestado']);
$router->get('/admin/gestion/compras/gestion/cambiarestado',[GestionOCController::class,'cambiarestado']);
$router->get('/admin/gestion/compras/gestion/cambiarestado',[GestionOCController::class,'cambiarestado']);

//== RECEPCION COMPRAS
$router->get('/admin/gestion/compras/recepcion',[RecepcionOCController::class,'index']);
$router->post('/admin/gestion/compras/orden/validarTipoCambio',[OrdenCompraController::class,'validarTipoCambio']);
$router->post('/admin/gestion/compras/orden/generar',[OrdenCompraController::class,'generar']);
$router->post('/admin/gestion/compras/orden/editar',[OrdenCompraController::class,'editar']);
$router->get('/admin/gestion/compras/orden/imprimir', [OrdenCompraController::class, 'imprimir']);

//== INVENTARIO
$router->get('/admin/gestion/inventarios/movimiento',[MovimientoController::class,'index']);
$router->post('/admin/gestion/inventarios/movimiento/generar',[MovimientoController::class,'generar']);
$router->post('/admin/gestion/inventarios/movimiento/editar',[MovimientoController::class,'editar']);
$router->get('/admin/gestion/inventarios/movimiento/imprimir', [MovimientoController::class, 'imprimir']);

//== GESTION inventario

$router->get('/admin/gestion/inventarios/gestion',[InventariosController::class,'index']);
$router->post('/admin/gestion/inventarios/gestion/anularmovimiento',[InventariosController::class,'anularmovimiento']);
$router->get('/admin/gestion/inventarios/gestion/anularmovimiento',[InventariosController::class,'anularmovimiento']);

//===== VENTAS
//== ORDEN VENTA
$router->get('/admin/gestion/ventas/orden',[OrdenVentaController::class,'index']);
$router->get('/admin/gestion/ventas/orden/validarTipoCambio',[OrdenVentaController::class,'validarTipoCambio']);
$router->post('/admin/gestion/ventas/orden/validarTipoCambio',[OrdenVentaController::class,'validarTipoCambio']);
$router->post('/admin/gestion/ventas/orden/generar',[OrdenVentaController::class,'generar']);
$router->post('/admin/gestion/ventas/orden/editar',[OrdenVentaController::class,'editar']);
$router->get('/admin/gestion/ventas/orden/imprimir', [OrdenVentaController::class, 'imprimir']);
//====GESTION ORDENES
$router->get('/admin/gestion/ventas/gestion',[GestionOrdenController::class,'index']);
$router->post('/admin/gestion/ventas/gestion/cambiarestado',[GestionOrdenController::class,'cambiarestado']);
$router->get('/admin/gestion/ventas/gestion/cambiarestado',[GestionOrdenController::class,'cambiarestado']);

//====REPORTES
$router->get('/admin/gestion/reportes',[ReportesController::class,'index']);

$router->get('/admin/gestion/reportes/fecha',[ReportesController::class,'fecha']);
$router->post('/admin/gestion/reportes/fecha',[ReportesController::class,'fecha']);
$router->post('/admin/gestion/reportes/pdffecha',[ReportesController::class,'pdffecha']);

$router->get('/admin/gestion/reportes/cliente',[ReportesController::class,'cliente']);
$router->post('/admin/gestion/reportes/cliente',[ReportesController::class,'cliente']);

$router->get('/admin/gestion/reportes/productos',[ReportesController::class,'productos']);
$router->post('/admin/gestion/reportes/productos',[ReportesController::class,'productos']);

$router->get('/admin/gestion/reportes/estado',[ReportesController::class,'estado']);
$router->post('/admin/gestion/reportes/estado',[ReportesController::class,'estado']);

$router->get('/admin/gestion/reportes/inventario',[ReportesController::class,'inventario']);
$router->post('/admin/gestion/reportes/inventario',[ReportesController::class,'inventario']);

$router->post('/admin/gestion/reportes/pdffecha', [ReportesController::class, 'pdffecha']);
$router->post('/admin/gestion/reportes/pdfcliente', [ReportesController::class, 'pdfcliente']);
$router->post('/admin/gestion/reportes/pdfproductos', [ReportesController::class, 'pdfproductos']);
$router->post('/admin/gestion/reportes/pdfestado', [ReportesController::class, 'pdfestado']);
$router->post('/admin/gestion/reportes/pdfinventario', [ReportesController::class, 'pdfinventario']);
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
$router->get('/admin/mantenimiento/productos/productos/activas', [ProductosController::class, 'activas']);
$router->get('/admin/mantenimiento/productos/productos/buscar', [ProductosController::class, 'buscar']);

//== PRODUCTOS TIENDA
$router->get('/admin/gestion/inventarios/tiendaproductos',[TiendaProductosController::class,'index']);
$router->post('/admin/gestion/inventarios/tiendaproductos/editar', [TiendaProductosController::class, 'editar']);

//===== LISTA DE PRECIOS
$router->get('/admin/mantenimiento/listas',[ListasController::class,'index']);
$router->post('/admin/mantenimiento/listas/crear', [ListasController::class, 'crear']);
$router->get('/admin/mantenimiento/listas/crear', [ListasController::class, 'crear']);
$router->post('/admin/mantenimiento/listas/editar', [ListasController::class, 'editar']);
$router->get('/admin/mantenimiento/listas/editar', [ListasController::class, 'editar']);
$router->post('/admin/mantenimiento/listas/eliminar', [ListasController::class, 'eliminar']);
$router->post('/admin/mantenimiento/listas/carga_masiva/cargar', [ListasController::class, 'cargar']);
$router->get('/admin/mantenimiento/listas/carga_masiva/cargar', [ListasController::class, 'cargar']);
//=======Asigancion de articulos a la lista de precios
$router->get('/admin/mantenimiento/listas/productosasignados', [ListasController::class, 'productosasignados']);
$router->post('/admin/mantenimiento/listas/asignarproducto',[ListasController::class, 'asignarproducto']);
$router->post('/admin/mantenimiento/listas/eliminarproducto',[ListasController::class, 'eliminarproducto']);
$router->post('/admin/mantenimiento/listas/actualizaproducto',[ListasController::class, 'actualizaproducto']);



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

//== PROVEEDORES
$router->get('/admin/mantenimiento/proveedores',[ProveedoresController::class,'index']);
$router->post('/admin/mantenimiento/proveedores/crear', [ProveedoresController::class, 'crear']);
$router->get('/admin/mantenimiento/proveedores/crear', [ProveedoresController::class, 'crear']);
$router->post('/admin/mantenimiento/proveedores/editar', [ProveedoresController::class, 'editar']);
$router->get('/admin/mantenimiento/proveedores/editar', [ProveedoresController::class, 'editar']);
$router->post('/admin/mantenimiento/proveedores/eliminar', [ProveedoresController::class, 'eliminar']);
$router->get('/admin/mantenimiento/proveedores/traerDocumento', [ProveedoresController::class, 'traerDocumento']);

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
$router->get('/admin/configuracion/tiendas/activas', [TiendasController::class, 'activas']);
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

//===== SERIES
$router->get('/admin/configuracion/series', [SeriesDocumentoController::class, 'index']);
$router->post('/admin/configuracion/series/crear', [SeriesDocumentoController::class, 'crear']);
$router->get('/admin/configuracion/series/crear', [SeriesDocumentoController::class, 'crear']);
$router->post('/admin/configuracion/series/editar', [SeriesDocumentoController::class, 'editar']);
$router->get('/admin/configuracion/series/editar', [SeriesDocumentoController::class, 'editar']);
$router->post('/admin/configuracion/series/eliminar', [SeriesDocumentoController::class, 'eliminar']);

//===== MOTIVO DEVOLUCION
$router->get('/admin/configuracion/devolucion', [DevolucionesController::class, 'index']);
$router->post('/admin/configuracion/devolucion/crear', [DevolucionesController::class, 'crear']);
$router->get('/admin/configuracion/devolucion/crear', [DevolucionesController::class, 'crear']);
$router->post('/admin/configuracion/devolucion/editar', [DevolucionesController::class, 'editar']);
$router->get('/admin/configuracion/devolucion/editar', [DevolucionesController::class, 'editar']);
$router->post('/admin/configuracion/devolucion/eliminar', [DevolucionesController::class, 'eliminar']);
$router->get('/admin/configuracion/devolucion/listar', [DevolucionesController::class, 'listar']);

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

$router->post('/admin/seguridad/usuarios/asignartienda',[UsuariosController::class, 'asignartienda']);
$router->post('/admin/seguridad/usuarios/eliminartienda',[UsuariosController::class, 'eliminartienda']);
$router->get('/admin/seguridad/usuarios/tiendasasignadas', [UsuariosController::class, 'tiendasasignadas']);















