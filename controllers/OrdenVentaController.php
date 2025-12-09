<?php

namespace Controllers;

use Model\FactorCambio;
use Model\Opciones;
use Model\OrdenVenta;
use MVC\Router;

require '../vendor/autoload.php';
// use PhpOffice\PhpSpreadsheet\IOFactory;


class OrdenVentaController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        date_default_timezone_set('America/Lima');
        $tc = FactorCambio::where('fecha',date("Y-m-d"));
        $simbolo_moneda = $_SESSION['simbolo_moneda'];
        $moneda = $_SESSION['moneda'];
        $tpago_defecto = $_SESSION['tpago_defecto'];
        $validar_tc = $_SESSION['validar_tc'];
        $router ->render('admin/gestion/ventas/orden/index',[
                'titulo' => 'Orden de Ventas',
                'opciones'=>$opciones,
                'moneda'=>$moneda,
                'tpago_defecto'=>$tpago_defecto,
                'simbolo_moneda'=>$simbolo_moneda,
                'validar_tc'=>$validar_tc,
                'opciones'=>$opciones,
                'tc' => $tc        
            ]);
    }

    public static function validarTipoCambio() {
        session_start();
        
        $fecha = $_POST['fecha'] ?? null;
        $idMoneda = $_POST['idMoneda'] ?? null;

        $monedaBase = $_SESSION['moneda'];
        $controlTC = $_SESSION['validar_tc'];

        if ($idMoneda == $monedaBase || $controlTC != 1) {
            echo json_encode(['success' => true, 'tc' => null]);
            return;
        }

        $FactorCambio = FactorCambio::where('fecha', $fecha, true);

        if ($FactorCambio) {
            echo json_encode([
                'success' => true,
                'tc' => $FactorCambio->venta_oficial
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    }


    public static function generar() {
        // Leer los datos JSON enviados desde JS
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'No se recibió información válida']);
            return;
        }

        try {
            // Convertimos el array nuevamente a JSON string para pasarlo al SP
            $jsonVenta = json_encode($data, JSON_UNESCAPED_UNICODE);

            // Llamamos a tu SP usando el ActiveRecord
            $resultado = OrdenVenta::procedureMantenimiento("prm_registrar_orden_venta", [$jsonVenta]);

            // Si no hay errores, respondemos con éxito
            echo json_encode([
                'success' => true,
                'message' => 'Orden de venta registrada correctamente.',
                // opcional: puedes devolver el número de OV o ID recién creada
                'numero' => $data['cabecera']['numero'] ?? null
            ]);
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar la OV: ' . $e->getMessage()
            ]);
        }
    }
    
}


