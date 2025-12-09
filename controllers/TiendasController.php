<?php

namespace Controllers;

use Model\Estados;
use Model\Opciones;
use Model\Tiendas;
use Model\UsuariosTiendas;
use MVC\Router;
require '../vendor/autoload.php';

class TiendasController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);     
        $tiendas = Tiendas::where('idempresa',$_SESSION['empresa'],false);        
        $router ->render('admin/configuracion/tiendas/index',[
                'titulo' => 'Tiendas',
                'tiendas'=>$tiendas,
                'alertas'=>$alertas,
                'opciones'=>$opciones        
            ]);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $tienda = new Tiendas;   
        $estados = Estados::where('idmaster','3',false);
        $tienda->idestado = 9; //  Activo por defecto    
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }
            $busca = Tiendas::where('codigo', $_POST['codigo'],false);
            $_POST['idempresa'] = $_SESSION['empresa'];
            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['empresa'];
            //leer imagen      
            
            $tienda->sincronizar($_POST);
            //validar
            $alertas = $tienda->validar();
            if($busca){
                $alertas['error'][] = 'CODIGO YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $tienda->guardar();
                if($resultado){
                    header('Location: /admin/configuracion/tiendas');
                }
            }
        }
        
      
        $router ->render('admin/configuracion/tiendas/crear',[
            'titulo' => 'Registrar Tienda',
            'alertas' => $alertas,     
            'tienda'=>$tienda,
            'estados' => $estados,
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
            header('Location: /admin/configuracion/tiendas');
        }       
        $tienda = Tiendas::find($id);
        $estados = Estados::where('idmaster','3',false);
        if(!$tienda){
            header('Location: /admin/configuracion/tiendas');
        }   

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
            $_POST['idempresa'] = $_SESSION['empresa'];
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $tienda->sincronizar($_POST);
            $_POST['idempresa'] = $_SESSION['empresa'];

            $alertas = $tienda->validar();

            if(empty($alertas)){
                $resultado = $tienda->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/configuracion/tiendas/editar',[
            'titulo' => 'Actualizar Tienda',
            'alertas' => $alertas,       
            'tienda'=>$tienda,
            'estados' => $estados,
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
            
            $tienda = Tiendas::find($id);      
            $busca = UsuariosTiendas::where('idtienda', $id);
            if(!isset($tienda)){
                header('Location: /admin/configuracion/tiendas');
            }
            
            if($busca){
                $alertas['error'][] = 'la tienda se encuentra asignada';
            }else{            
                $resultado = $tienda->eliminar();           
                    if($resultado){
                        header('Location: /admin/configuracion/tiendas'); 
                    }     
             
            } 

            $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);     
            $tiendas = Tiendas::where('idempresa',$_SESSION['empresa'],false);        
            $router ->render('admin/configuracion/tiendas/index',[
                    'titulo' => 'Tiendas',
                    'tiendas'=>$tiendas,
                    'alertas'=>$alertas,
                    'opciones'=>$opciones        
                ]);

        }
    }

    

}