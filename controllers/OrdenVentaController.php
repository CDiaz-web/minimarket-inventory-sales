<?php

namespace Controllers;

use Model\Monedas;
use Model\FactorCambio;
use Model\Opciones;
use Model\OrdenVenta;
use Model\OrdenVentaDetalle;
use Model\Empresa;
use MVC\Router;

require '../vendor/autoload.php';

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
        $lista_monedas = Monedas::all('ASC');
        $router ->render('admin/gestion/ventas/orden/index',[
                'titulo' => 'Orden de Ventas',
                'opciones'=>$opciones,
                'moneda'=>$moneda,
                'tpago_defecto'=>$tpago_defecto,
                'simbolo_moneda'=>$simbolo_moneda,
                'lista_monedas'=>$lista_monedas,
                'validar_tc'=>$validar_tc,
                'opciones'=>$opciones,
                'tc' => $tc        
            ]);
    }

   

    public static function validarTipoCambio() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $fecha = $_POST['fecha'] ?? null;
        $idMoneda = $_POST['idMoneda'] ?? null;

        $monedaBase = $_SESSION['moneda'];
        $controlTC = $_SESSION['validar_tc'];

        // Si la empresa no valida TC
        if ($controlTC != 1) {
            echo json_encode([
                'success' => true,
                'requiere_tc' => false,
                'tc' => null
            ]);
            return;
        }

        $FactorCambio = FactorCambio::where('fecha', $fecha, true);

        if ($FactorCambio) {
            echo json_encode([
                'success' => true,
                'requiere_tc' => true,
                'tc' => $FactorCambio->venta_mercado
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'requiere_tc' => true,
                'tc' => null
            ]);
        }
    }
    
    public static function generar() {

        try {
            $data = json_decode(file_get_contents('php://input'), true);

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

            if (!$idTienda || !$idUsuario) {
                throw new \Exception('Sesión no válida');
            }

            // 👉 completar cabecera desde backend
            $data['cabecera']['idtienda']  = $idTienda;
            $data['cabecera']['idusercrea'] = $idUsuario;

            $jsonVenta = json_encode($data, JSON_UNESCAPED_UNICODE);

            $resultado = OrdenVenta::procedureMantenimiento(
                "prm_registrar_orden_venta",
                [$jsonVenta]
            );

            // 👇 convertir mysqli_result a array
            $fila = $resultado->fetch_assoc();

            if (!$fila) {
                throw new \Exception('No se pudo obtener resultado del procedimiento');
            }

            echo json_encode([
                'ok'      => true,
                'idorden' => $fila['idorden'],
                'numero'  => $fila['numero']
            ]);


        } catch (\Throwable $e) {

            http_response_code(500);

            echo json_encode([
                'ok'      => false,
                'mensaje' => $e->getMessage(),
                'trace'   => $e->getLine()
            ]);
        }
    }


    public static function imprimir(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (empty($_GET['id'])) {
            echo "<h4>Error: No se especificó el ID de la Orden de Venta.</h4>";
            exit;
        }

        $idorden = (int) $_GET['id'];

        try {

            // Empresa
            $empresa = Empresa::find($_SESSION['idempresa']);

            // Cabecera
            $cabecera = OrdenVenta::procedureLista(
                'prc_orden_venta_impresion',
                [$idorden, 1]
            );
            $cabecera = $cabecera[0] ?? null;

            // Detalle
            $detalle = OrdenVentaDetalle::procedureLista(
                'prc_orden_venta_impresion',
                [$idorden, 2]
            );

            if (!$cabecera) {
                echo "<h4>No se encontró la orden solicitada.</h4>";
                exit;
            }

            if (empty($detalle)) {
                echo "<h4>La orden no tiene detalle.</h4>";
                exit;
            }

            // Renderizar plantilla
            ob_start();
            include __DIR__ . '/../views/admin/gestion/ventas/orden/pdf_template.php';
            $html = ob_get_clean();

            // DOMPDF
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $dompdf->stream(
                "Orden_Venta_{$cabecera->numero}.pdf",
                ['Attachment' => false]
            );

        } catch (\Throwable $e) {
            echo "<h3>Error al generar PDF</h3>";
            echo "<pre>{$e->getMessage()}</pre>";
        }
    }



    
}


