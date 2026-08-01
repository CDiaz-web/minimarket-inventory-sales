<?php

namespace Controllers;

use Model\Estados;
use Model\Opciones;
use Model\Productos;
use Model\Unidades;
use Model\SeriesDocumento;
use MVC\Router;
use Model\Tiendas;
use Model\TipoDocumentos;

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
        $valor = $_SESSION['idempresa'];  
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $tiendas = Tiendas::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];   
        $documentos = TipoDocumentos::where('activo',1,false);
        $serie = new SeriesDocumento(); 

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }            
     
            $busca = SeriesDocumento::findArray(['idempresa'=> $_SESSION['idempresa'],'serie'=> $_POST['serie'],'idtipodocumento'=> $_POST['idtipodocumento']],false) ?? [];   

            $existe_predeterminado = SeriesDocumento::findArray(['idempresa'=> $_SESSION['idempresa'],'idtienda'=> $_SESSION['idtienda'],'idtipodocumento'=> $_POST['idtipodocumento'],'predeterminado'=> '1'],false) ?? [];   

            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $predeterminado = isset($_POST['predeterminado']) ? 1 : 0;
            $_POST['predeterminado'] = $predeterminado;
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;
            
            $serie->sincronizar($_POST);
            //validar
            $alertas = $serie->validar();

            if($busca){
                $alertas['error'][] = 'SERIE YA REGISTRADO';
            }
            if($existe_predeterminado && $_POST['predeterminado'] =='1' ){
                $alertas['error'][] = 'EXISTE UNA SERIE POR DEFECTO PARA ESTE TIPO DE DOCUMENTO EN ESTA TIENDA';
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $serie->guardar();
                if($resultado){
                    header('Location: /admin/configuracion/series');
                }
            }
        }
        
      
        $router ->render('admin/configuracion/series/crear',[
            'titulo' => 'Registrar Serie',
            'alertas' => $alertas, 
            'tiendas' => $tiendas,     
            'documentos' => $documentos, 
            'serie'=>$serie,
            'opciones'=>$opciones        
  
        ]);
    }

    public static function editar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
         $valor = $_SESSION['idempresa'];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $tiendas = Tiendas::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];   
        $documentos = TipoDocumentos::where('activo',1,false);
        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            header('Location: /admin/configuracion/series');
        }       
        $serie = SeriesDocumento::find($id);
        if(!$serie){
            header('Location: /admin/configuracion/series');
        }   

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post

            $existe_predeterminado = SeriesDocumento::findArray(['idempresa'=> $_SESSION['idempresa'],'idtienda'=> $_SESSION['idtienda'],'idtipodocumento'=> $_POST['idtipodocumento'],'predeterminado'=> '1'],false) ?? [];   
  
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $predeterminado = isset($_POST['predeterminado']) ? 1 : 0;
            $_POST['predeterminado'] = $predeterminado;
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;
            $serie->sincronizar($_POST);

            $alertas = $serie->validar();

            if($existe_predeterminado && $_POST['predeterminado'] =='1' ){
                $alertas['error'][] = 'EXISTE UNA SERIE POR DEFECTO PARA ESTE TIPO DE DOCUMENTO EN ESTA TIENDA';
            }

            if(empty($alertas)){
                $resultado = $serie->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/configuracion/series/editar',[
            'titulo' => 'Actualizar Serie',
            'alertas' => $alertas, 
            'tiendas' => $tiendas,     
            'documentos' => $documentos, 
            'serie'=>$serie,
            'opciones'=>$opciones        
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $valor = [$_SESSION['idempresa']]; 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $serie = SeriesDocumento::find($id);      
            $busca = Productos::where('idunidad_medida', $id);
            if(!isset($serie)){
                header('Location: /admin/configuracion/series');
            }

            // if($busca){
            //     $alertas['error'][] = 'Serie en uso';
            // }else{            
                $resultado = $serie->eliminar();
            
                if($resultado){
                    header('Location: /admin/configuracion/series'); 
                }   
            // }             

            $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
            $series = SeriesDocumento::procedureLista('prc_series_listar',$valor); 
            
            $router ->render('admin/configuracion/series/index',[
                'titulo' => 'Series',
                'series'=>$series,
                'alertas'=>$alertas,
                'opciones'=>$opciones       
                ]);

        }
    }    

}