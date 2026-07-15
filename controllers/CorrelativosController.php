<?php

namespace Controllers;

use MVC\Router;
use Model\Categorias;
use Model\Correlativos;
use Model\Estados;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use Model\Opciones;
use Model\Productos;
use Model\Tiendas;

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


class CorrelativosController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $alertas = [];
        $valor = [$_SESSION['idempresa']];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);  
        $correlativos = Correlativos::procedureLista('prc_ListaCorrelativos',$valor);

        $router ->render('admin/configuracion/correlativos/index',[
                'titulo' => 'Correlativos por Tienda',
                'alertas' => $alertas,
                'correlativos'=>$correlativos,
                'opciones'=>$opciones        
            ]);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $valor = $_SESSION['idempresa'];  
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $correlativo = new Correlativos;    
        $tiendas = Tiendas::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $busca = Correlativos::findArray(['idtienda'=> $_POST['idtienda'],'tipo_documento'=> $_POST['tipo_documento']],true) ?? [];

            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
          
            $_POST['updated_at']=date("Y-m-d H:i:s");
            $_POST['ultimo_numero']=0;
       
           
            $correlativo->sincronizar($_POST);
            //validar

            $alertas = $correlativo->validar();
            if($busca){
                $alertas['error'][] = 'DOCUMENTO PARA ESTA TIENDA YA HA SIDO CREADO';
            }
            //guardar el registro
            if(empty($alertas)){

                //guardar en la base de datos
                $resultado = $correlativo->guardar();
                if($resultado){
                    header('Location: /admin/configuracion/correlativos');
                }
            }
        }
        
      
        $router ->render('admin/configuracion/correlativos/crear',[
            'titulo' => 'Registrar Correlativo',
            'alertas' => $alertas,     
            'correlativo'=>$correlativo,
            'tiendas'=>$tiendas,
            'opciones'=>$opciones       
  
        ]);
    }

    
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $valor = [$_SESSION['idempresa']];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);  
        $correlativos = Correlativos::procedureLista('prc_ListaCorrelativos',$valor);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $correlativo = Correlativos::find($id);      

            $busca = Correlativos::findArrayOperador([
                ['campo' => 'id',  'operador' => '=','valor' => $id],
                ['campo' => 'ultimo_numero', 'operador' => '>', 'valor' => 0]
            ], true);
            
            if(!isset($correlativo)){
                header('Location: /admin/configuracion/correlativos');
            }
            if($busca){
                $alertas['error'][] = 'el registro no puede ser eliminado';
            }else{
                $resultado = $correlativo->eliminar();
            
                if($resultado){
                    header('Location: /admin/configuracion/correlativos'); 
                } 
            }

            // Renderizamos la vista con las alertas
            $router ->render('admin/configuracion/correlativos/index',[
                'titulo' => 'Correlativos por Tienda',
                'alertas' => $alertas,
                'correlativos'=>$correlativos,
                'opciones'=>$opciones        
            ]);        
      
        }
    }

   
}