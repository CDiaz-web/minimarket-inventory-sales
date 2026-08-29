<?php

namespace Controllers;

use Model\Monedas;
use Model\FactorCambio;
use Model\Opciones;
use Model\OrdenCompra;
use Model\OrdenCompraDetalle;
use Model\Empresa;
use Model\Estados;
use Model\SeriesDocumento;
use Model\TipoDocumentos;
use MVC\Router;

require '../vendor/autoload.php';

class RecepcionOCController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }  
        
        $idOrden = isset($_GET['id']) ? (int) $_GET['id'] : 0;    

        $cabecera = null;
        $detalle = [];
        $modoEdicion = false;
        $idEmpresa = $_SESSION['idempresa'];
        $idTienda = $_SESSION['idtienda'];

        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);   
        $empresa = Empresa::where('id',$_SESSION['idempresa']);   

        $tipodocumentos = TipoDocumentos::findArray(['idempresa'=> $_SESSION['idempresa'],'activo'=> 1,'comprobante_pago'=> 1],false) ?? [];
        
        /*serie por defecto*/
        $datos_series =SeriesDocumento::procedure(
                'prc_serie_defecto',
                [$idEmpresa,$idTienda,'VEN']
        );      

        $serie_defecto = $datos_series->id;        
        date_default_timezone_set('America/Lima');
               

        $lista_series = SeriesDocumento::procedureLista(
                'prc_lista_series',
                [$idEmpresa,$idTienda,'VEN']
        );      
        


        $fecha =  date('Y-m-d');
        $titulo = 'Recepcion Orden Compra';


        $router ->render('admin/gestion/compras/recepcion/index',[
                'titulo' => $titulo,
                'opciones'=>$opciones,                
                'serie_defecto'=>$serie_defecto,  
                'lista_series'=>$lista_series,
                'tipodocumentos'=>$tipodocumentos,
                'modoEdicion'=>$modoEdicion,
                'fecha'=>$fecha
            ]);
    }
   
public static function generar() {

    header('Content-Type: application/json; charset=utf-8');

    try {

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!$data) {
            throw new \Exception('JSON inválido o vacío');
        }

        if (empty($data['detalle'])) {
            throw new \Exception('Detalle vacío');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $idTienda  = $_SESSION['idtienda'] ?? null;
        $idUsuario = $_SESSION['id'] ?? null;
        $idEmpresa = $_SESSION['idempresa'] ?? null;

        if (!$idTienda || !$idUsuario || !$idEmpresa) {
            throw new \Exception('Sesión no válida');
        }

        // ==========================================
        // COMPLETAR CABECERA DESDE BACKEND
        // ==========================================

        $data['cabecera']['idtienda']  = $idTienda;
        $data['cabecera']['idusercrea'] = $idUsuario;
        $data['cabecera']['idempresa'] = $idEmpresa;

        $jsonCompra = json_encode(
            $data,
            JSON_UNESCAPED_UNICODE
        );

        // ==========================================
        // EJECUTAR PROCEDIMIENTO
        // ==========================================

        $resultado = OrdenCompra::procedureMantenimiento(
            'prp_inventario_compra',
            [$jsonCompra]
        );

        $fila = $resultado->fetch_assoc();

        if (!$fila) {
            throw new \Exception(
                'No se pudo obtener resultado del procedimiento'
            );
        }

        // ==========================================
        // RESPUESTA
        // ==========================================

        echo json_encode([
            'ok' => true,
            'idinvent' => $fila['idinvent'],
            'numero_formateado' => $fila['numero_formateado'],
            'numero' => $fila['numero']
        ], JSON_UNESCAPED_UNICODE);

    } catch (\Throwable $e) {

        http_response_code(500);

        echo json_encode([
            'ok' => false,
            'mensaje' => $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}
    
    
   


    
}
