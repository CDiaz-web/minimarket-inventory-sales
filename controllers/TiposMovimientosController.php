<?php

namespace Controllers;

use Model\Estados;
use Model\Opciones;
use Model\TiposMovimientos;
use Model\Unidades;
use MVC\Router;

require '../vendor/autoload.php';

class TiposMovimientosController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        // $tipos = TiposMovimientos::all('ASC');
        $tipos = TiposMovimientos::where('idempresa',$_SESSION['idempresa'],false);   
        $router ->render('admin/configuracion/tipo_movimiento/index',[
            'titulo' => 'Tipos Movimientos Inventario',
            'tipos'=>$tipos,
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
        $tipo = new TiposMovimientos;   
 
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }            
            $busca = TiposMovimientos::where('codigo', $_POST['codigo'],false);

            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;    
            $_POST['mov_manual'] = '1';    
            $tipo->sincronizar($_POST);
            //validar
            $alertas = $tipo->validar();
            if($busca){
                $alertas['error'][] = 'CODIGO YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $tipo->guardar();
                if($resultado){
                    header('Location: /admin/configuracion/tipo_movimiento');
                }
            }
        }
        
      
        $router ->render('admin/configuracion/tipo_movimiento/crear',[
            'titulo' => 'Registrar Tipo Movimiento',
            'alertas' => $alertas,     
            'tipo'=>$tipo,
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
            header('Location: /admin/configuracion/tipo_movimiento');
        }       
        $tipo = TiposMovimientos::find($id);
        if(!$tipo){
            header('Location: /admin/configuracion/tipo_movimiento');
        }          
     
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
  
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;    
            $tipo->sincronizar($_POST);

            $alertas = $tipo->validar();

            if($tipo->es_sistema == "1"){
                $alertas['error'][] = 'REGISTRO NO PUEDE SER MODIFICACO';
            }

            if(empty($alertas)){
                $resultado = $tipo->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';      
                  
                }
            }
            
        }

        $router ->render('admin/configuracion/tipo_movimiento/editar',[
            'titulo' => 'Actualizar Tipo Movimiento',
            'alertas' => $alertas,       
            'tipo'=>$tipo,
            'opciones'=>$opciones        
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $tipos = TiposMovimientos::all('ASC');        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $tipo = TiposMovimientos::find($id);      
            
            if(!isset($tipo)){
                header('Location: /admin/configuracion/tipo_movimiento');
            }
            if($tipo->es_sistema == "1"){
                $alertas['error'][] = 'REGISTRO NO PUEDE SER ELIMINADO';
            }else{
                $resultado = $tipo->eliminar();
            
                if($resultado){
                    header('Location: /admin/configuracion/tipo_movimiento'); 
                }   
            }      
                  
            // Renderizamos la vista con las alertas
            $router ->render('admin/configuracion/tipo_movimiento/index',[
                'titulo' => 'Tipos Movimientos Inventario',
                'alertas' => $alertas,  
                'tipos'=>$tipos,
                'opciones'=>$opciones        
            ]);

    
        }
    }

    

}