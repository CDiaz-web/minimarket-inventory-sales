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
        // Año y mes actuales por defecto
        $anio = $_GET['anio'] ?? date("Y");
        $mes = $_GET['mes'] ?? date("m");
        $idtienda = $_SESSION['idtienda'];        
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $inventarios = Inventarios::procedureLista('prc_ListaMovInventario',[$idtienda, $anio, $mes]);
        $alertas = [];
        $router ->render('admin/gestion/logistica/inventario/index',[
                'titulo' => 'Movimientos Tienda',
                'inventarios'=>$inventarios,
                'alertas'=>$alertas,
                'anio' =>$anio,
                'mes' =>$mes,
                'opciones'=>$opciones        
            ]);
    }

     // Mostrar formulario para crear
    public static function crear(Router $router) {
        if(!is_auth()){
            header('Location: /login');
            return;
        }        
        $inventario = new Inventarios();
        $detalles = []; // vacío al inicio
        $tiposmovimiento = TiposMovimientos::findArray(['es_generado'=> 0,'idestado'=> 9,'tipo_documento'=> 'Inventario'],false) ?? [];// devuelve tipos que no se generen de manera automatica
        $tiendas = Tiendas::where('idestado',9);
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $valor = [$_SESSION['idempresa']]; 
        $productos = Productos::procedureLista('prc_ListaProductos',$valor);
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cabecera
            $inventario = new Inventarios($_POST['cabecera']);
      
            // Validar cabecera
            $errores = $inventario->validar();
            
            // Detalle (viene como array desde el JS)
            $detallePost = $_POST['detalle'] ?? [];
            $detalle = [];
            
            if (empty($detallePost)) {
                $errores[] = "Debe agregar al menos un producto en el detalle.";
            }
                        // debuguear($_POST['detalle']);
            if ($_SESSION['idtienda'] == $inventario->idtienda_relacion){        
                $alertas['error'][] = 'La tienda dstino debe ser diferente a la tienda destino';             
            }
        
            foreach ($detallePost as $item) {
                $det = new InventarioDetalle($item);
                $errores = array_merge($errores, $det->validar());
                $detalle[] = $det;
            }
  
            if (empty($errores)) {

                // Si viene vacío o '', enviamos NULL
                $idtienda_relacion = (isset($inventario->idtienda_relacion) && $inventario->idtienda_relacion !== '')
                                    ? (int)$inventario->idtienda_relacion
                                    : null;                    
                // Llamada al SP con ActiveRecord
                $valores = [
                    1, // opcion 1 para el sp 
                    0, // id = 0 porque es nuevo
                    $inventario->numero = 0,
                    $inventario->idtienda = $_SESSION['idtienda'] ,
                    $inventario->codigotipo,
                    $inventario->fecha,
                    $inventario->observacion,                 
                    $idtienda_relacion,
                    $inventario->idestado = 1,
                    $inventario->idusercrea = $_SESSION['id'],
                    json_encode($detalle, JSON_UNESCAPED_UNICODE)
                ];

                if(empty($alertas)){
                    Inventarios::procedureMantenimiento("prm_movimiento_inventarios", $valores);

                    header('Location: /admin/gestion/logistica/inventario');
                }
            
            }
        }

        $router->render('admin/gestion/logistica/inventario/crear', [
            'titulo' => 'Nuevo Movimiento',
            'inventario' => $inventario,
            'opciones' => $opciones,
            'tiendas' => $tiendas,
            'tiposmovimiento' => $tiposmovimiento,
            'productos'=> $productos,
            'detalles' => $detalles,
            'alertas' => Inventarios::getAlertas()
        ]);
    }  
    
    // Editar un movimiento existente
    public static function editar(Router $router) {
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $id = $_GET['id'];
        
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            header('Location: /admin/gestion/logistica/inventario');
        }    
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);            

        $valor = [$id]; 
        $productos = Productos::procedureLista('prc_DetalleMovimientoInventario',$valor);   


        if(!$productos){
            header('Location: /admin/gestion/logistica/inventario');
        }          

        $router ->render('admin/gestion/logistica/inventario/editar',[
            'titulo' => 'Actualizar Movimiento',
            'alertas' => $alertas,       
            'productos'=>$productos,     
            'opciones'=>$opciones
        ]);
    }

    // Editar un movimiento existente
    public static function editar_registro(Router $router) {
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $id = $_GET['id'];
        
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            header('Location: /admin/gestion/logistica/inventario');
        }    
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);            
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $iddetalle = $_POST['iddetalle'];
            $cantidad = $_POST['cantidad'];
            $costo = $_POST['costo'];
            $usuario = $_SESSION['id'];

            $valor = [
                $iddetalle,
                $cantidad,
                $costo,
                $usuario
            ];
            debuguear('hola');
            $resultado = Productos::procedureMantenimiento('prm_detalle_inventario_editar',$valor);  
            if($resultado){
                header('Location: /admin/gestion/logistica/inventario/editar'); 
            }    
        }




        $router ->render('admin/gestion/logistica/inventario/editar',[
            'titulo' => 'Actualizar Movimiento',
            'alertas' => $alertas,       
            'productos'=>$productos,     
            'opciones'=>$opciones
        ]);
    }


    // Anular un movimiento
    public static function anular(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        // Año y mes actuales por defecto
        $anio = $_GET['anio'] ?? date("Y");
        $mes = $_GET['mes'] ?? date("m");
        $idtienda = $_SESSION['idtienda'];        
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $inventarios = Inventarios::procedureLista('prc_ListaMovInventario',[$idtienda, $anio, $mes]);
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];         
            $inventario = Inventarios::find($id); 

            //debuguear($busca);
            $idtienda = $_SESSION['idtienda'];
            if(!isset($inventario)){
                header('Location: /admin/gestion/logistica/inventario');
            }           
           
            $valores = ["2",$id,"0",$idtienda,"",date('Y-m-d H:i:s'),"",null,2,$_SESSION['id'],'[]']; 
            
            try {
                $resultado = $inventario->procedureMantenimiento('prm_movimiento_inventarios',$valores);

                if($resultado){
                    header('Location: /admin/gestion/logistica/inventario'); 
                }  

            } catch (\Exception $e) {
                // Captura el SIGNAL del procedimiento
                $alertas['error'][] = $e->getMessage();
            }
            $router ->render('admin/gestion/logistica/inventario/index',[
                'titulo' => 'Movimientos Tienda',
                'inventarios'=>$inventarios,
                'alertas'=>$alertas,
                'anio' =>$anio,
                'mes' =>$mes,
                'opciones'=>$opciones        
            ]);          
        }
    }

    public static function imprimir(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo "<h4>Error: No se especificó el ID del movimiento.</h4>";
            exit;
        }

        $idmovimiento = intval($_GET['id']);
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        try {
            // === Info Empresa ===
            $empresa = Empresa::find($_SESSION['idempresa']);
            // === Cabecera ===
            $cabecera = Inventarios::procedureLista('prc_imprime_movimiento', [$idmovimiento, 1]);
            $cabecera = !empty($cabecera) ? $cabecera[0] : null;

            // === Detalle ===
            $detalle = InventarioDetalle::procedureLista('prc_imprime_movimiento', [$idmovimiento, 2]);

            if (!$cabecera) {
                echo "<h4>No se encontró información para el movimiento solicitado.</h4>";
                exit;
            }

            // === Generar HTML desde plantilla ===
            ob_start();

            include __DIR__ . "/../views/admin/gestion/logistica/inventario/pdf_template.php";

        
            $html = ob_get_clean();

            // === Configurar DOMPDF ===
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // === Mostrar el PDF en el navegador (sin descargar) ===
            $dompdf->stream("Movimiento_{$cabecera->id}.pdf", ["Attachment" => false]);

        } catch (\Throwable $e) {
            echo "<h3>Error al generar PDF</h3>";
            echo "<pre>{$e->getMessage()}</pre>";
        }
    }





}