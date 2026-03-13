<?php

namespace Controllers;

use Model\Empresa;
use Model\FactorCambio;
use Model\Monedas;
use Model\Opciones;
use MVC\Router;



require '../vendor/autoload.php';



class FactorCambioController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        // Año y mes actuales por defecto
        $anio = $_GET['anio'] ?? date("Y");
        $mes = $_GET['mes'] ?? date("m");
        $idempresa = $_SESSION['idempresa']; // Supongo que la guardas en sesión


        
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $factores = FactorCambio::procedureLista('prc_ListaTipoCambio',[$idempresa, $anio, $mes]);        
        $alertas = [];
        
        $router ->render('admin/mantenimiento/factor/index',[
                'titulo' => 'Tipo de Cambio',
                'factores'=>$factores,
                'alertas'=>$alertas,
                'anio' =>$anio,
                'mes' =>$mes,
                'opciones'=>$opciones        
            ]);
    }

    public function obtenerTipoCambioPorFecha($fecha) {
        $tc = FactorCambio::where('fecha',$fecha);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }

        $empresa = Empresa::where('id',$_SESSION['idempresa']);   
        $variaciontc= $empresa[0]->variaciontc;
   
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $factor = new FactorCambio;   
        $monedas = Monedas::all('ASC'); 
        $factor->idmoneda_origen = 2; // soles    
        $factor->idmoneda_destino = 1; // dolares
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }            
            $valor = $_SESSION['idempresa'];
            $origen = $_POST['idmoneda_origen'];
            $destino = $_POST['idmoneda_destino'];
            $fechabuscar = $_POST['fecha'];
            // //agregamos informacion de auditoria al $_post
            $busca = FactorCambio::findArray(['idempresa'=> $valor,'fecha'=> $fechabuscar,'idmoneda_origen'=> $origen,'idmoneda_destino'=> $destino],true) ?? [];
          
            $_POST['idempresa'] = $_SESSION['idempresa'];
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            //leer imagen      
            
            $factor->sincronizar($_POST);
            //validar
            $alertas = $factor->validar();

            if($busca){
                $alertas['error'][] = 'FECHA YA REGISTRADA';
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $factor->guardar();
                if($resultado){
                    header('Location: /admin/mantenimiento/factor');
                }
            }
        }
        
      
        $router ->render('admin/mantenimiento/factor/crear',[
            'titulo' => 'Registrar tipo de Cambio',
            'alertas' => $alertas,     
            'factor'=>$factor,
            'monedas' => $monedas,
            'variaciontc'=> $variaciontc,
            'opciones'=>$opciones        
  
        ]);
    }

    public static function editar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            header('Location: /admin/mantenimiento/factor');
        }       
        $factor = FactorCambio::find($id);
        $monedas = Monedas::all('ASC'); 
        if(!$factor){
            header('Location: /admin/mantenimiento/factor');
        }   

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
            $_POST['idempresa'] = $_SESSION['idempresa'];
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $factor->sincronizar($_POST);

            $alertas = $factor->validar();

            if(empty($alertas)){
                $resultado = $factor->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/mantenimiento/factor/editar',[
            'titulo' => 'Actualizar Tipo de Cambio',
            'alertas' => $alertas,       
            'factor'=>$factor,
            'monedas' => $monedas,
            'opciones'=>$opciones        
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $factor = FactorCambio::find($id);      
         
            if(!isset($factor)){
                header('Location: /admin/mantenimiento/factor');
            }
          
            $resultado = $factor->eliminar();
        
            if($resultado){
                header('Location: /admin/mantenimiento/factor'); 
            }   


        }
    }

    public static function traerSUNAT() {
        header('Content-Type: application/json');

        // Unificamos: ahora siempre 'fecha'
        $fecha = $_GET['date'] ?? date('Y-m-d');

        $url = 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=' . $fecha;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_SSL_VERIFYPEER => false, // evitar problemas SSL en localhost
            CURLOPT_SSL_VERIFYHOST => false
        ]);

        $respuesta = curl_exec($curl);

        if ($respuesta === false) {
            echo json_encode(['error' => 'Error al consultar SUNAT: ' . curl_error($curl)]);
            return;
        }

        echo $respuesta;
    }
    

}