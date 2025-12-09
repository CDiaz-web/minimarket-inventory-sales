<?php

namespace Controllers;

use Model\Empresas;
use Model\Opciones;
use Model\Perfiles;
use Model\Tiendas;
use Model\UsuariosTiendas;
use MVC\Router;

class EligeTiendaController{

    public static function index(Router $router){
        if(is_auth()){
            $id = $_SESSION['id'];
            $id = filter_var($id,FILTER_VALIDATE_INT);
           
            if(!$id || $id < 1){
                echo json_encode([]);
                return;
            }
            $valores = [$id]; 
            
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
         
                            
                $idtienda = $_POST['idtienda'];         
                $tienda = Tiendas::find($idtienda);  
                $perfil =Perfiles::find($_SESSION['idperfil']);         
                $opcion =Opciones::find($perfil->inicial);
                
                
                
                session_start();    
                $_SESSION['idtienda'] =  $idtienda ;
                $_SESSION['tienda'] = $tienda->nombre;     
                header('Location: /admin' . $opcion->vista);                    
              
            }
          
            $tiendas = UsuariosTiendas::procedureLista('prc_ListaTiendasUsuario',$valores);
            //$tiendas = Tiendas::all();
            $router ->render('tiendas/index',[
                'titulo' => 'Seleccione Tienda',        
                'tiendas'=>$tiendas
            ]);
        }

    }

}