<?php

namespace Controllers;
require '../vendor/autoload.php';

use MVC\Router;
use Model\Opciones;
use Model\Tiendas;
use Model\UsuariosTiendas;

class UsuariosTiendasController {
    public static function index(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }       

        $valor = [$_GET['id']];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        
        $tiendas = UsuariosTiendas::procedureLista('prc_ListaTiendasUsuario',$valor);
       
        $router ->render('admin/seguridad/usuarios/tiendas/index',[
            'titulo' => 'Agregar Tiendas',  
            'tiendas'=>$tiendas,          
            'opciones'=>$opciones            
        ]);
    }
    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];
        $idusuario = $_GET['id'];
        $tiendas = Tiendas::where('idestado',9);
        $usuario_tiendas = new UsuariosTiendas;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }
            //$usuario_tiendas = UsuariosTiendas::whereArray('prc_ListaTiendasUsuario',$valor);
            $idtienda = $_POST['idtienda'];
            
            $usuario_tienda = UsuariosTiendas::findArray(['idusuario'=> $idusuario,'idtienda'=> $idtienda],false) ?? [];
      
            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusuario']=$idusuario;
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");            
    
            //leer imagen      
            $usuario_tiendas->sincronizar($_POST);
            //validar
           
            $alertas = $usuario_tiendas->validar();
            if($usuario_tienda) {
                UsuariosTiendas::setAlerta('error', 'La Tienda ya fue registrada');
                $alertas = UsuariosTiendas::getAlertas();
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar las imagenes

                //guardar en la base de datos
                $resultado = $usuario_tiendas->guardar();
                if($resultado){
                    header('Location: /admin/seguridad/usuarios/tiendas?id=' . $idusuario);
                }
            }
        }        
      
        $router ->render('admin/seguridad/usuarios/tiendas/crear',[
            'titulo' => 'Registrar Tienda',
            'alertas' => $alertas,       
            'tiendas'=>$tiendas,
            'opciones'=>$opciones
        ]);
    }

    
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
       
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $idtienda = $_POST['id'];               
            $usuario_tienda = UsuariosTiendas::find($idtienda);    
           // $user = $$usuario_tienda->idusuario;
            if(!isset($usuario_tienda)){
              
                header('Location: /admin/seguridad/usuarios/tiendas');                 
            }
            
           $resultado = $usuario_tienda->eliminar($idtienda);
          
            if($resultado){
                
               $cadena = 'Location: /admin/seguridad/usuarios/tiendas?id='  .  $usuario_tienda->idusuario;
               //debuguear($cadena);
               header($cadena); 
            }       
        }
    }
    
}