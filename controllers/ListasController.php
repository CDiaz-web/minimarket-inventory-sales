<?php

namespace Controllers;

use Model\Estados;
use Model\Listas;
use Model\Monedas;
use Model\Opciones;
use Model\TipoCliente;
use Model\Unidades;
use MVC\Router;

require '../vendor/autoload.php';



class ListasController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $listas = Listas::where('idempresa',$_SESSION['empresa'],false);
        $alertas = [];
        $router ->render('admin/mantenimiento/listas/index',[
                'titulo' => 'Lista de Precios',
                'listas'=>$listas,
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
        $lista = new Listas;   
        $monedas = Monedas::all('ASC');
        $estados = Estados::where('idmaster','3',false);
        $lista->idestado = 9; //  Activo por defecto  
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }     
            $busca = Listas::where('codigo', $_POST['codigo'],false);       
            $_POST['idempresa'] = $_SESSION['empresa'];
            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            //leer imagen      
            
            $lista->sincronizar($_POST);
            //validar
            $alertas = $lista->validar();
            if($busca){
                $alertas['error'][] = 'CODIGO YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $lista->guardar();
                if($resultado){
                    header('Location: /admin/mantenimiento/listas');
                }
            }
        }
        
      
        $router ->render('admin/mantenimiento/listas/crear',[
            'titulo' => 'Registrar Lista de Precios',
            'alertas' => $alertas,     
            'lista'=>$lista,
            'monedas'=>$monedas,
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
            header('Location: /admin/mantenimiento/listas');
        }       
        $lista = listas::find($id);
        $monedas = Monedas::all('ASC');
        $estados = Estados::where('idmaster','3',false);
         
        if(!$lista){
            header('Location: /admin/mantenimiento/listas');
        }   

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
  
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $lista->sincronizar($_POST);

            $alertas = $lista->validar();

            if(empty($alertas)){
                $resultado = $lista->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/mantenimiento/listas/editar',[
            'titulo' => 'Actualizar Lista de Precio',
            'alertas' => $alertas,       
            'lista'=>$lista,
            'monedas'=>$monedas,
            'estados'=>$estados,
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
          
            $lista = Listas::find($id);      
            $busca = TipoCliente::where('idlista', $id);
            if(!isset($lista)){
                header('Location: /admin/mantenimiento/listas');
            }
         
            
            if($busca){
                $alertas['error'][] = 'La lista ha sido asignada  auna clasificacion de clientes';
            }else{            
                $resultado = $lista->eliminar();           
                if($resultado){
                    header('Location: /admin/mantenimiento/listas'); 
                }    
            }
                $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
                $listas = Listas::where('idempresa',$_SESSION['empresa'],false);
                $router ->render('admin/mantenimiento/listas/index',[
                    'titulo' => 'Lista de Precios',
                    'listas'=>$listas,
                    'alertas'=>$alertas,
                    'opciones'=>$opciones        
                ]);

        }
    }

    

}