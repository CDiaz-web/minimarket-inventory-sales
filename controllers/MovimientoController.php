<?php

namespace Controllers;

use Model\TiposMovimientos;
use Model\Opciones;
use Model\Inventarios;
use Model\InventarioDetalle;
use Model\OrdenCompra;
use Model\OrdenCompraDetalle;
use Model\Empresa;
use Model\Estados;
use Model\Tiendas;
use MVC\Router;

require '../vendor/autoload.php';

class MovimientoController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }  
        
        $idOrden = isset($_GET['id']) ? (int) $_GET['id'] : 0;    

        $cabecera = null;
        $detalle = [];
        $modoEdicion = false;

        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);   
        $empresa = Empresa::where('id',$_SESSION['idempresa']);   
        $impuesto= $empresa[0]->porcentaje_imp;       
        
        date_default_timezone_set('America/Lima');
     
        $simbolo_moneda = $_SESSION['simbolo_moneda'];
        $moneda = $_SESSION['moneda'];
        $validar_tc = $_SESSION['validar_tc'];
        $tipos_movimientos = TiposMovimientos::findArray(['idempresa'=> $_SESSION['idempresa'],'activo'=> 1,'mov_manual'=> 1],false) ?? [];

        

        $tiendas = Tiendas::findArrayOperador([
            ['campo' => 'idempresa',  'operador' => '=','valor' => $_SESSION['idempresa']],
            ['campo' => 'activo', 'operador' => '=', 'valor' => 1],
            ['campo' => 'id', 'operador' => '!=', 'valor' => $_SESSION['idtienda']]
        ], false);
            
      
        $fecha =  date('Y-m-d');
        $titulo = 'Movimiento de Inventario';
        if (!empty($idOrden)) {
      
            $cabecera = OrdenCompra::procedureLista(
                'prc_lista_orden_compra_edicion',
                [$idOrden, 1]
            );      
            $cabecera = $cabecera[0] ?? null;

            $titulo = 'Edición Orden Compra N° ' . $cabecera->numero;
            
            $detalle = OrdenCompraDetalle::procedureLista(
                'prc_lista_orden_compra_edicion',
                [$idOrden, 2]
            );    
            
            $modoEdicion = true;
        }
        

        $router ->render('admin/gestion/inventarios/movimiento/index',[
                'titulo' => $titulo,
                'opciones'=>$opciones,                
                'tipos_movimientos'=>$tipos_movimientos,
                'fecha'=>$fecha,
                'validar_tc'=>$validar_tc,
                'impuesto'=>$impuesto,
                'opciones'=>$opciones,
                'cabecera' => $cabecera,
                'tiendas' => $tiendas,
                'detalle' => $detalle
            ]);
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
            $idEmpresa = $_SESSION['idempresa'] ?? null;

            if (!$idTienda || !$idUsuario || !$idEmpresa) {
                throw new \Exception('Sesión no válida');
            }

            // completa cabecera desde backend
            $data['cabecera']['idtienda']  = $idTienda;
            $data['cabecera']['idusercrea'] = $idUsuario;
            $data['cabecera']['idempresa'] = $idEmpresa;

            $jsonCompra = json_encode($data, JSON_UNESCAPED_UNICODE);

            $resultado = Inventarios::procedureMantenimiento(
                "prp_inventario_registrar",
                [$jsonCompra]
            );

            // convertir mysqli_result a array
            $fila = $resultado->fetch_assoc();

            if (!$fila) {
                throw new \Exception('No se pudo obtener resultado del procedimiento');
            }

            echo json_encode([
                'ok'      => true,
                'idmovimiento' => $fila['idmovimiento'],
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
            echo "<h4>Error: No se especificó el ID del Movimiento Inventario</h4>";
            exit;
        }

        $idmovimiento = (int) $_GET['id'];

        try {

            // Empresa
            $empresa = Empresa::find($_SESSION['idempresa']);

            $idTienda  = $_SESSION['idtienda'] ?? null;            
            $idEmpresa = $_SESSION['idempresa'] ?? null;

            // Cabecera
            $cabecera = Inventarios::procedureLista(
                'prc_inventario_imprimir',
                [$idEmpresa,$idTienda,$idmovimiento, 1]
            );
            $cabecera = $cabecera[0] ?? null;

            // Detalle
            $detalle = InventarioDetalle::procedureLista(
                'prc_inventario_imprimir',
                [$idEmpresa,$idTienda,$idmovimiento, 2]
            );

            if (!$cabecera) {
                echo "<h4>No se encontró el movimiento solicitada.</h4>";
                exit;
            }

            if (empty($detalle)) {
                echo "<h4>El movimiento no tiene detalle.</h4>";
                exit;
            }

            // Renderizar plantilla
            ob_start();
            include __DIR__ . '/../views/admin/gestion/inventarios/movimiento/pdf_template.php';
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
                "Orden_Compra_{$cabecera->numero}.pdf",
                ['Attachment' => false]
            );

        } catch (\Throwable $e) {
            echo "<h3>Error al generar PDF</h3>";
            echo "<pre>{$e->getMessage()}</pre>";
        }
    }



    
}
