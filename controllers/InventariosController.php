<?php

namespace Controllers;
use Dompdf\Dompdf;
use Dompdf\Options;
use Model\Empresa;
use Model\Inventarios;
use Model\InventarioDetalle;
use Model\Opciones;
use Model\Productos;
use Model\Tiendas;
use Model\TiposMovimientos;
use MVC\Router;
require '../vendor/autoload.php';

class InventariosController {
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
        $inventarios = Inventarios::procedureLista('prc_inventario_listar',[$idempresa,$idtienda,  $fechaInicio, $fechaFin]);
        $alertas = [];        
        $router ->render('admin/gestion/inventarios/gestion/index',[
                'titulo' => 'Movimientos Tienda',
                'inventarios'=>$inventarios,
                'alertas'=>$alertas,
                'filtros' => $filtros,   
                'opciones'=>$opciones        
            ]);
    }

    public static function anularmovimiento() {

        try {

            // Leer JSON
            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data) {
                throw new \Exception('JSON inválido');
            }

            if (empty($data['id']) || empty($data['motivo_anulacion'])) {
                throw new \Exception('Datos incompletos');
            }
            

            // Validar sesión
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $idTienda  = $_SESSION['idtienda'] ?? null;
            $idEmpresa  = $_SESSION['idempresa'] ?? null;
            $idUsuario = $_SESSION['id'] ?? null;

            if (!$idTienda || !$idEmpresa || !$idUsuario) {
                throw new \Exception('Sesión no válida');
            }

            

            $idMovimiento = intval($data['id']);
         
            $buscar = Inventarios::find($idMovimiento);
            error_log(json_encode($data));
            if($buscar->idestado === '13'){
                echo json_encode([
                    'error' => true,
                    'mensaje' => 'No se puede modificar una orden anulada'
                ]);
                return;
            }


            // Si es anulación y manejas motivo
            $Motivo = $data['motivo_anulacion'] ?? null;

            // Llamar SP
            $resultado = Inventarios::procedureMantenimiento(
                "prm_anula_movimiento_inventario",
                [$idTienda, $idEmpresa, $idMovimiento,$Motivo,$idUsuario]
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