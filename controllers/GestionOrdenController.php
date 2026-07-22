<?php

namespace Controllers;

use Model\Empresa;
use Model\OrdenVenta;   
use Model\Opciones;


use MVC\Router;
require '../vendor/autoload.php';

class GestionOrdenController {
    // lista los movimientos del periodo
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }        

        $idempresa = $_SESSION['idempresa'];     
        $idtienda = $_SESSION['idtienda'];      

        $fechaInicio = $_GET['fecha_inicial'] ?? date('Y-m-d');
        $fechaFin    = $_GET['fecha_final'] ?? date('Y-m-d');
        $filtros = [
            'fecha_inicial' => $fechaInicio,
            'fecha_final'   => $fechaFin
        ];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $ordenes = OrdenVenta::procedureLista('prc_venta_listar',[$idempresa,$idtienda, $fechaInicio, $fechaFin]);
        
        $alertas = [];
        $router ->render('admin/gestion/ventas/gestion/index',[
                'titulo' => 'Gestión de Ordenes de Venta',
                'ordenes'=>$ordenes,
                'alertas'=>$alertas,
                'filtros' => $filtros,                
                'opciones'=>$opciones        
            ]);
    }

  
    public static function cambiarestado() {

        try {

            // Leer JSON
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data) {
                throw new \Exception('JSON inválido');
            }

            if (empty($data['id']) || empty($data['estado'])) {
                throw new \Exception('Datos incompletos');
            }
            

            // Validar sesión
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $idTienda  = $_SESSION['idtienda'] ?? null;
            $idUsuario = $_SESSION['id'] ?? null;

            if (!$idTienda || !$idUsuario) {
                throw new \Exception('Sesión no válida');
            }

            

            $idOrden = intval($data['id']);
            $estado  = $data['estado'];

            $buscar = OrdenVenta::find($idOrden);

            if($buscar->idestado === '2'){
                echo json_encode([
                    'error' => true,
                    'mensaje' => 'No se puede modificar una orden anulada'
                ]);
                return;
            }


            // Si es anulación y manejas motivo
            $idMotivo = $data['idmotivo'] ?? null;

            // Llamar SP
            $resultado = OrdenVenta::procedureMantenimiento(
                "prm_cambia_estado_orden",
                [$idOrden, $idTienda, $estado, $idMotivo,$idUsuario]
            );

            echo json_encode([
                'ok' => true
            ]);

        } catch (\Throwable $e) {

            http_response_code(500);

            echo json_encode([
                'ok'      => false,
                'mensaje' => $e->getMessage(),
                'trace'   => $e->getLine() // solo debug
            ]);
        }
    }
   


}