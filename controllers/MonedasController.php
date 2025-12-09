<?php

namespace Controllers;

use Model\Monedas;
use Model\Opciones;
use MVC\Router;



require '../vendor/autoload.php';



class MonedasController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $monedas = Monedas::all('ASC');
        
        $router ->render('admin/tablas/monedas/index',[
                'titulo' => 'Monedas',
                'monedas'=>$monedas,
                'opciones'=>$opciones        
            ]);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $moneda = new Monedas;   
        
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
            
            $moneda->sincronizar($_POST);
            //validar
            $alertas = $moneda->validar();

            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $moneda->guardar();
                if($resultado){
                    header('Location: /admin/tablas/monedas');
                }
            }
        }
        
      
        $router ->render('admin/tablas/monedas/crear',[
            'titulo' => 'Registrar Moneda',
            'alertas' => $alertas,     
            'moneda'=>$moneda,
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
            header('Location: /admin/tablas/monedas');
        }       
        $moneda = Monedas::find($id);
        if(!$moneda){
            header('Location: /admin/tablas/monedas');
        }   

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
  
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $moneda->sincronizar($_POST);

            $alertas = $moneda->validar();

            if(empty($alertas)){
                $resultado = $moneda->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/tablas/monedas/editar',[
            'titulo' => 'Actualizar Moneda',
            'alertas' => $alertas,       
            'moneda'=>$moneda,
            'opciones'=>$opciones        
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $moneda = Monedas::find($id);      
            
            if(!isset($moneda)){
                header('Location: /admin/tablas/monedas');
            }

           $resultado = $moneda->eliminar();
           
            if($resultado){
                header('Location: /admin/tablas/monedas'); 
            }       
        }
    }

    

}