<?php

namespace Controllers;

use Model\Estados;
use Model\Listas;
use Model\Opciones;
use Model\TipoCliente;
use MVC\Router;



require '../vendor/autoload.php';



class TipoClientesController{
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $tipos = TipoCliente::where('idempresa',$_SESSION['idempresa'],false);   
        
        $router ->render('admin/mantenimiento/clientes/clasificacion/index',[
                'titulo' => 'Clasificacion de Clientes',
                'tipos'=>$tipos,
                'opciones'=>$opciones        
            ]);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $tipo = new TipoCliente; 
        $valor = $_SESSION['idempresa'];  
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }            
            $busca = TipoCliente::where('codigo', $_POST['codigo'],false);  
            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;
            //leer imagen      
            
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
                    header('Location: /admin/mantenimiento/clientes/clasificacion');
                }
            }
        }
        
      
        $router ->render('admin/mantenimiento/clientes/clasificacion/crear',[
            'titulo' => 'Registrar Clasificacion',
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
            header('Location: /admin/mantenimiento/clientes/clasificacion');
        }       
        $tipo = TipoCliente::find($id);
        $valor = $_SESSION['idempresa'];  
        if(!$tipo){
            header('Location: /admin/mantenimiento/clientes/clasificacion');
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

            if(empty($alertas)){
                $resultado = $tipo->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/mantenimiento/clientes/clasificacion/editar',[
            'titulo' => 'Actualizar Clasificacion',
            'alertas' => $alertas,       
            'tipo'=>$tipo, 
            'opciones'=>$opciones        
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $tipo = TipoCliente::find($id);      
            
            if(!isset($tipo)){
                header('Location: /admin/mantenimiento/clientes/clasificacion');
            }

           $resultado = $tipo->eliminar();
           
            if($resultado){
                header('Location: /admin/mantenimiento/clientes/clasificacion'); 
            }       
        }
    }

    

}