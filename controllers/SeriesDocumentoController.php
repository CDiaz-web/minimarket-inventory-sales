<?php

namespace Controllers;

use Model\Estados;
use Model\Opciones;
use Model\Productos;
use Model\Unidades;
use Model\SeriesDocumento;
use MVC\Router;

require '../vendor/autoload.php';

class SeriesDocumentoController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $valor = [$_SESSION['idempresa']]; 
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);         
        $series = SeriesDocumento::procedureLista('prc_series_listar',$valor); 
        $alertas = [];
        $router ->render('admin/configuracion/series/index',[
                'titulo' => 'Series',
                'series'=>$series,
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
        $unidad = new Unidades; 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }            
     
            $busca = Unidades::where('codigo', $_POST['codigo'],false);
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;
            
            $unidad->sincronizar($_POST);
            //validar
            $alertas = $unidad->validar();

            if($busca){
                $alertas['error'][] = 'CODIGO YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $unidad->guardar();
                if($resultado){
                    header('Location: /admin/configuracion/unidad');
                }
            }
        }
        
      
        $router ->render('admin/configuracion/unidad/crear',[
            'titulo' => 'Registrar Unidad de Medida',
            'alertas' => $alertas,     
            'unidad'=>$unidad,
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
            header('Location: /admin/configuracion/unidad');
        }       
        $unidad = Unidades::find($id);
        if(!$unidad){
            header('Location: /admin/configuracion/unidad');
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
            $unidad->sincronizar($_POST);

            $alertas = $unidad->validar();

            if(empty($alertas)){
                $resultado = $unidad->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/configuracion/unidad/editar',[
            'titulo' => 'Actualizar Unidad de Medida',
            'alertas' => $alertas,       
            'unidad'=>$unidad,
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
            
            $unidad = Unidades::find($id);      
            $busca = Productos::where('idunidad_medida', $id);
            if(!isset($unidad)){
                header('Location: /admin/configuracion/unidad');
            }

            if($busca){
                $alertas['error'][] = 'Unidad de medida en uso';
            }else{            
                $resultado = $unidad->eliminar();
            
                if($resultado){
                    header('Location: /admin/configuracion/unidad'); 
                }   
            }             

            $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
            $unidades = Unidades::all('ASC');
            
            $router ->render('admin/configuracion/unidad/index',[
                    'titulo' => 'Unidad de Medida',
                    'unidades'=>$unidades,
                    'alertas'=>$alertas,
                    'opciones'=>$opciones        
                ]);

        }
    }    

}