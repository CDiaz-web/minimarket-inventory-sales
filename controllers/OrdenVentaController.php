<?php

namespace Controllers;

use Model\Monedas;
use Model\FactorCambio;
use Model\Opciones;
use Model\OrdenVenta;
use Model\OrdenVentaDetalle;
use Model\SeriesDocumento;
use Model\Empresa;
use MVC\Router;

require '../vendor/autoload.php';

class OrdenVentaController {
    
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
        $impuesto= $empresa[0]->porcentaje_imp;  

        /*serie por defecto*/
        $datos_series =SeriesDocumento::procedure(
                'prc_serie_defecto',
                [$idEmpresa,$idTienda,'OVE']
        );  

        $serie_defecto = $datos_series->id;

        date_default_timezone_set('America/Lima');
        $tc = FactorCambio::where('fecha',date("Y-m-d"));
        $simbolo_moneda = $_SESSION['simbolo_moneda'];
        $moneda = $_SESSION['moneda'];
        $tpago_defecto = $_SESSION['tpago_defecto'];
        $validar_tc = $_SESSION['validar_tc'];
        $lista_monedas = Monedas::all('ASC');
        $titulo = 'Registro Orden Venta';
        $fecha =  date('Y-m-d');

        $lista_series = SeriesDocumento::procedureLista(
                'prc_lista_series',
                [$idEmpresa,$idTienda,'OVE']
        );   

        if (!empty($idOrden)) {
      
            $cabecera = OrdenVenta::procedureLista(
                'prc_lista_orden_venta_edicion',
                [$idEmpresa,$idTienda,$idOrden, 1]
            );      
            $cabecera = $cabecera[0] ?? null;

            $titulo = 'Edición Orden Venta N° ' . $cabecera->numero;
             
            $detalle = OrdenVentaDetalle::procedureLista(
                'prc_lista_orden_venta_edicion',
                [$idEmpresa,$idTienda,$idOrden, 2]
            );    
            
            $modoEdicion = true;
        }

        $router ->render('admin/gestion/ventas/orden/index',[
                'titulo' => $titulo,
                'opciones'=>$opciones,
                'moneda'=>$moneda,
                'serie_defecto'=>$serie_defecto,  
                'simbolo_moneda'=>$simbolo_moneda,
                'lista_monedas'=>$lista_monedas,
                'lista_series'=>$lista_series,
                'validar_tc'=>$validar_tc,
                'impuesto'=>$impuesto,
                'cabecera' => $cabecera,
                'detalle' => $detalle,
                'opciones'=>$opciones,
                'fecha'=>$fecha,
                'tc' => $tc        
            ]);
    }

   

    public static function validarTipoCambio()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $fecha = $_POST['fecha'] ?? null;
            $idMoneda = (int) ($_POST['idMoneda'] ?? 0);

            // Datos de la empresa obtenidos de la sesión
            $idEmpresa = (int) ($_SESSION['idempresa'] ?? 0);
            $idMonedaBase = (int) ($_SESSION['moneda'] ?? 0);
            $validarTC = (int) ($_SESSION['validar_tc'] ?? 0);

            // Validar datos necesarios
            if (!$fecha || !$idMoneda || !$idEmpresa || !$idMonedaBase) {
                throw new \Exception(
                    'Faltan datos para consultar el tipo de cambio.'
                );
            }

            // Si el documento utiliza la moneda base, no necesita conversión
            // if ($idMoneda === $idMonedaBase) {
            //     echo json_encode([
            //         'success' => true,
            //         'requiere_tc' => false,
            //         'tc' => 1,
            //         'tc_oficial' => 1
            //     ]);
            //     return;
            // }

            // Buscar el tipo de cambio por fecha y empresa
            $factorCambio = FactorCambio::findArray(
                [
                    'fecha' => $fecha,
                    'idempresa' => $idEmpresa
                ],
                true
            );

            if ($factorCambio) {
                $tcOperacion = (float) $factorCambio->venta_mercado;
                $tcOficial = (float) $factorCambio->venta_oficial;

                // Validar que ambos tipos de cambio sean positivos
                if ($tcOperacion <= 0 || $tcOficial <= 0) {
                    throw new \Exception(
                        'El tipo de cambio registrado no es válido.'
                    );
                }

                echo json_encode([
                    'success' => true,
                    'requiere_tc' => (bool) $validarTC,
                    'tc' => $tcOperacion,
                    'tc_oficial' => $tcOficial
                ]);
                return;
            }

            // No existe tipo de cambio para la fecha y empresa
            echo json_encode([
                'success' => false,
                'requiere_tc' => (bool) $validarTC,
                'tc' => null,
                'tc_oficial' => null,
                'mensaje' => 'No existe tipo de cambio registrado para la fecha seleccionada.'
            ]);

        } catch (\Throwable $e) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'mensaje' => $e->getMessage()
            ]);
        }
    }    




    
    public static function generar()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Leer y validar el JSON recibido
            $data = json_decode(
                file_get_contents('php://input'),
                true
            );

            if (
                json_last_error() !== JSON_ERROR_NONE ||
                !is_array($data)
            ) {
                throw new \Exception('JSON inválido o vacío');
            }

            if (
                !isset($data['cabecera']) ||
                !is_array($data['cabecera'])
            ) {
                throw new \Exception(
                    'La cabecera de la orden es inválida'
                );
            }

            if (
                !isset($data['detalle']) ||
                !is_array($data['detalle']) ||
                count($data['detalle']) === 0
            ) {
                throw new \Exception(
                    'La orden debe contener al menos un artículo'
                );
            }

            // Obtener el contexto desde la sesión
            $idEmpresa = (int) ($_SESSION['idempresa'] ?? 0);
            $idTienda  = (int) ($_SESSION['idtienda'] ?? 0);
            $idUsuario = (int) ($_SESSION['id'] ?? 0);

            if (
                $idEmpresa <= 0 ||
                $idTienda <= 0 ||
                $idUsuario <= 0
            ) {
                throw new \Exception('Sesión no válida');
            }

            // Los datos de contexto no se aceptan del navegador
            $data['cabecera']['idempresa'] = $idEmpresa;
            $data['cabecera']['idtienda'] = $idTienda;
            $data['cabecera']['idusercrea'] = $idUsuario;

            // Preparar el JSON para el procedimiento almacenado
            $jsonVenta = json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            );

            // Generar la orden de venta
            $resultado = OrdenVenta::procedureMantenimiento(
                'prp_orden_venta_generar',
                [$jsonVenta]
            );

            if (!$resultado || !method_exists($resultado, 'fetch_assoc')) {
                throw new \Exception(
                    'No se pudo obtener el resultado del procedimiento'
                );
            }

            $fila = $resultado->fetch_assoc();

            if (
                !$fila ||
                empty($fila['idorden']) ||
                !isset(
                    $fila['idserie'],
                    $fila['serie'],
                    $fila['numero'],
                    $fila['numero_formateado']
                )
            ) {
                throw new \Exception(
                    'El procedimiento no devolvió todos los datos de la orden generada'
                );
            }

            // Respuesta al frontend
            echo json_encode([
                'ok' => true,
                'idorden' => (int) $fila['idorden'],
                'idserie' => (int) $fila['idserie'],
                'serie' => $fila['serie'],
                'numero' =>  $fila['numero'],
                'numero_formateado' => $fila['numero_formateado']
            ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        } catch (\Throwable $e) {
            http_response_code(500);

            echo json_encode([
                'ok' => false,
                'mensaje' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }



    public static function editar()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $data = json_decode(
                file_get_contents('php://input'),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (!is_array($data)) {
                throw new \Exception('Datos de edición inválidos');
            }

            if (empty($data['cabecera']['idorden'])) {
                throw new \Exception('Debe indicar la orden de venta que desea editar');
            }

            if (empty($data['detalle']) || !is_array($data['detalle'])) {
                throw new \Exception('El detalle de la orden está vacío');
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

            // Los datos de contexto y auditoría se establecen en el backend.
            $data['cabecera']['idtienda'] = (int) $idTienda;
            $data['cabecera']['idempresa'] = (int) $idEmpresa;
            $data['cabecera']['idusercrea'] = (int) $idUsuario;

            $jsonVenta = json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            );

            $resultado = OrdenVenta::procedureMantenimiento(
                'prp_orden_venta_editar',
                [$jsonVenta]
            );

            if (!$resultado || !method_exists($resultado, 'fetch_assoc')) {
                throw new \Exception(
                    'El procedimiento no devolvió un resultado válido'
                );
            }

            $fila = $resultado->fetch_assoc();

            if (!$fila) {
                throw new \Exception(
                    'No se pudo obtener el resultado de la edición'
                );
            }

        echo json_encode([
            'ok' => true,
            'idorden' => (int) $fila['idorden'],
            'idserie' => (int) $fila['idserie'],
            'serie' => $fila['serie'],
            'numero' => $fila['numero_documento'],
            'numero_formateado' => $fila['numero_formateado']
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        } catch (\Throwable $e) {
            http_response_code(500);

            echo json_encode([
                'ok' => false,
                'mensaje' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
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


