<?php

namespace Controllers;

use Model\Estados;
use Model\Opciones;
use Model\OrdenVenta;
use Model\TipoPago;
use MVC\Router;



require '../vendor/autoload.php';



class TipoPagoController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $tipagos = TipoPago::all('ASC');
        $alertas = [];
        $router ->render('admin/configuracion/tipopago/index',[
                'titulo' => 'Tipos de Pago',
                'tipagos'=>$tipagos,
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
        $tipopago = new TipoPago;   
        $estados = Estados::where('idmaster','3',false);
        $tipopago->idestado = 9; //  Activo por defecto    
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }            
     
            // //agregamos informacion de auditoria al $_post
            $busca = TipoPago::where('codigo', $_POST['codigo'],false);
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            //leer imagen      
            
            $tipopago->sincronizar($_POST);
            //validar
            $alertas = $tipopago->validar();

            if($busca){
                $alertas['error'][] = 'CODIGO YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $tipopago->guardar();
                if($resultado){
                    header('Location: /admin/configuracion/tipopago');
                }
            }
        }
        
      
        $router ->render('admin/configuracion/tipopago/crear',[
            'titulo' => 'Registrar Tipo de Pago',
            'alertas' => $alertas,     
            'tipopago'=>$tipopago,
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
            header('Location: /admin/configuracion/tipopago');
        }       
        $tipopago = TipoPago::find($id);
        $estados = Estados::where('idmaster','3',false);
        if(!$tipopago){
            header('Location: /admin/configuracion/tipopago');
        }   

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
  
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $tipopago->sincronizar($_POST);

            $alertas = $tipopago->validar();

            if(empty($alertas)){
                $resultado = $tipopago->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/configuracion/tipopago/editar',[
            'titulo' => 'Actualizar Tipo de Pago',
            'alertas' => $alertas,       
            'tipopago'=>$tipopago,
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
            
            $tipopago = TipoPago::find($id);    
          
            $busca = OrdenVenta::where('idtipopago', $id);
            if(!isset($tipopago)){
                header('Location: /admin/configuracion/tipopago');
            }

            if($busca){
                $alertas['error'][] = 'Tipo de Pago en uso';
            }else{            
                $resultado = $tipopago->eliminar();
            
                if($resultado){
                    header('Location: /admin/configuracion/tipopago'); 
                }   
            }             

            $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
            $tipagos = TipoPago::all('ASC');
            
            $router ->render('admin/configuracion/tipopago/index',[
                    'titulo' => 'Tipos de Pago',
                    'tipagos'=>$tipagos,
                    'alertas'=>$alertas,
                    'opciones'=>$opciones        
                ]);

        }
    }

    

}