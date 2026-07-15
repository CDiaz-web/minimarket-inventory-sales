<?php

namespace Controllers;

use Model\Empresa;
use Model\Monedas;
use Model\TipoDocumentos;
use Model\TipoPago;
use MVC\Router;
use Intervention\Image\ImageManagerStatic as Image;
use Model\Opciones;

require '../vendor/autoload.php';



class EmpresaController {
 
    public static function editar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $id = $_SESSION['idempresa'];
               
        $empresa = Empresa::find($id);
      
        if(!$empresa){
            header('Location: /admin/dashboard');
        }   
        $monedas = Monedas::all('ASC');
        $tipos = TipoPago::all('ASC');
        
        $empresa->logo_actual = $empresa->logo; 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
           
            if(!empty($_FILES['logo']['tmp_name'])){
                $carpeta_imagenes = '../public/img';
                //crear la carpeta de o exixtir               
                if(!is_dir($carpeta_imagenes)){
                    mkdir($carpeta_imagenes,0777,true);
                }

                $imagen_png = Image::make($_FILES['logo']['tmp_name'])->fit(800,800)->encode('png',80);
                $imagen_webp = Image::make($_FILES['logo']['tmp_name'])->fit(800,800)->encode('webp',80);
                
                $nombre_imagen = md5(uniqid(rand(), true));
                //eliminamos el archivo anterior
                unlink($carpeta_imagenes . '/' . $empresa->logo_actual . ".png");
                unlink($carpeta_imagenes . '/' . $empresa->logo_actual . ".webp");
                //end eliminamos el archivo anterior
                $_POST['logo'] = $nombre_imagen;
                

            } else{
                $_POST['logo'] = $empresa->logo_actual;
            }             


            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $ultimo = isset($_POST['validar_tc']) ? 1 : 0;
            $_POST['validar_tc'] = $ultimo;

            $ov_aprobacion = isset($_POST['ov_requiere_aprobacion']) ? 1 : 0;
            $_POST['ov_requiere_aprobacion'] = $ov_aprobacion;

            $oc_aprobacion = isset($_POST['oc_requiere_aprobacion']) ? 1 : 0;
            $_POST['oc_requiere_aprobacion'] = $oc_aprobacion;

            $empresa->sincronizar($_POST);
            
            $alertas = $empresa->validar();

            //debuguear($_POST);
            
            if(empty($alertas)){
                //guardar las imagenes
                if(isset($nombre_imagen)){
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");                              
                }
               
         
                $resultado = $empresa->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/configuracion/empresa/editar',[
            'titulo' => 'Informacion Empresa',
            'alertas' => $alertas,
            // 'tdocumentos'=>$tdocumentos,
            'monedas'=>$monedas,
            'tipos'=>$tipos,
            // 'tipospago'=>$tipospago,       
            'empresa'=>$empresa,
            'opciones'=>$opciones
        ]);
    }
    

}