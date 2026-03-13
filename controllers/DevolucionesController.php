<?php

namespace Controllers;

use Model\Devoluciones;
use Model\Estados;
use Model\Opciones;
use Model\TiposMovimientos;
use MVC\Router;



require '../vendor/autoload.php';



class DevolucionesController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $devoluciones = Devoluciones::all('ASC');
        $router ->render('admin/configuracion/devolucion/index',[
            'titulo' => 'Motivos de Devolucion',
            'devoluciones'=>$devoluciones,
            'alertas' => $alertas, 
            'opciones'=>$opciones        
        ]);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $devolucion = new Devoluciones;   
        $estados = Estados::where('idmaster','3',false);
        $devolucion->idestado = 9; //  Activo por defecto  
        // $tipos_relacionados = TiposMovimientos::where('idestado','9',false);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }            
     
            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            //leer imagen      
            
            $devolucion->sincronizar($_POST);
            //validar
            $alertas = $devolucion->validar();

            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $devolucion->guardar();
                if($resultado){
                    header('Location: /admin/configuracion/devolucion');
                }
            }
        }
        
      
        $router ->render('admin/configuracion/devolucion/crear',[
            'titulo' => 'Registrar Motivo Devolucion',
            'alertas' => $alertas,     
            'devolucion'=>$devolucion,
            'estados'=>$estados,          
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
            header('Location: /admin/configuracion/devolucion');
        }       
        $devolucion = Devoluciones::find($id);
        if(!$devolucion){
            header('Location: /admin/configuracion/devolucion');
        }   
        $estados = Estados::where('idmaster','3',false);
        // $tipos_relacionados = TiposMovimientos::where('idestado','9',false);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
  
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $devolucion->sincronizar($_POST);

            $alertas = $devolucion->validar();

            if(empty($alertas)){
                $resultado = $devolucion->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';      
                  
                }
            }
            
        }

        $router ->render('admin/configuracion/devolucion/editar',[
            'titulo' => 'Actualizar Motivo Devolucion',
            'alertas' => $alertas,       
            'devolucion'=>$devolucion,
            'estados'=>$estados,            
            'opciones'=>$opciones        
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $devoluciones = Devoluciones::all('ASC');        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $devolucion = Devoluciones::find($id);      
            
            if(!isset($devolucion)){
                header('Location: /admin/configuracion/devolucion');
            }
            // if($tipo->es_sistema == "1"){
            //     $alertas['error'][] = 'REGISTRO NO PUEDE SER ELIMINADO';
            // }else{
                $resultado = $devolucion->eliminar();
            
                if($resultado){
                    header('Location: /admin/configuracion/devolucion'); 
                }   
            // }      
                  
            // Renderizamos la vista con las alertas
            $router ->render('admin/configuracion/devolucion/index',[
                'titulo' => 'Tipos Movimientos Inventario',
                'alertas' => $alertas,  
                'devoluciones'=>$devoluciones,
                'opciones'=>$opciones        
            ]);

    
        }
    }    

    public static function listar(Router $router)
{

    $motivos = Devoluciones::where('idestado','9',false);
    $respuesta = [];

    foreach ($motivos as $m) {
        $respuesta[$m->id] = $m->nombre;
    }

    echo json_encode($respuesta);
}


}