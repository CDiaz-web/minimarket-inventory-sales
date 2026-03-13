<?php

namespace Controllers;
require '../vendor/autoload.php';

use MVC\Router;
use Model\Cajas;
use Model\Estados;
use Model\Opciones;
use Model\Perfiles;
use Model\PerfilOpciones;
use Model\Tiendas;
use Model\Usuario;
use Model\UsuariosTiendas;

class PerfilesController {
    public static function index(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }       

        // $productos = Productos::all('ASC');
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $perfiles = Perfiles::where('idempresa',$_SESSION['idempresa'],false);
        $router ->render('admin/seguridad/perfiles/index',[
            'titulo' => 'Perfiles',
            'perfiles'=>$perfiles,
            'alertas'=>$alertas,
            'opciones'=>$opciones            
        ]);
    }
    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];
        $perfil = new Perfiles;       
           
        $estados = Estados::where('idmaster','3',false);
        $perfil->idestado = 9; //  Activo por defecto          
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
            $_POST['idempresa'] = $_SESSION['empresa'];
            //leer imagen      
            $perfil->sincronizar($_POST);
            //validar
           
            $alertas = $perfil->validar();
            $valida_nombre = Perfiles::where('nombre', $perfil->nombre);
           
            if($valida_nombre){
                $alertas['error'][] = 'PERFIL YA REGISTRADO'; 
            }
            
            //guardar el registro
            if(empty($alertas)){
                //guardar las imagenes

                //guardar en la base de datos
                $resultado = $perfil->guardar();
                if($resultado){
                    header('Location: /admin/seguridad/perfiles');
                }
            }
        }
        
      
        $router ->render('admin/seguridad/perfiles/crear',[
            'titulo' => 'Registrar Perfil',
            'alertas' => $alertas,
            'perfil' => $perfil,
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
            header('Location: /admin/seguridad/perfiles');
        }       
        $perfil = Perfiles::find($id);
        if(!$perfil){
            header('Location: /admin/seguridad/perfiles');
        }          
        $estados = Estados::where('idmaster','3',false);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            
        
            //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['empresa'];
            $perfil->sincronizar($_POST);

            $alertas = $perfil->validar();

            if(empty($alertas)){                
                
                $resultado = $perfil->guardar();            
                if($resultado){             
                    $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA'; 
                   //header('Location: /admin/logistica/productos');
                }
            }
            
        }

        $router ->render('admin/seguridad/perfiles/editar',[
            'titulo' => 'Actualizar Perfil',
            'alertas' => $alertas,
            'perfil' => $perfil,
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
            $opciones = new PerfilOpciones;
            $perfil = Perfiles::find($id);      
            $busca = Usuario::where('idperfil', $id);
            if(!isset($perfil)){
                header('Location: /admin/seguridad/perfiles');
            }            

            if($busca){
                $alertas['error'][] = 'El perfil esta siendo usado';
            }else{            
                $valores = ["1",0,$id,"0"];              
                $resultado = $opciones->procedureMantenimiento('prm_OpcionesPerfil',$valores);
                $resultado = $perfil->eliminar();           
                if($resultado){
                    header('Location: /admin/seguridad/perfiles'); 
                }   
            }  
        
            $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
            $perfiles = Perfiles::where('idempresa',$_SESSION['empresa'],false);
            $router ->render('admin/seguridad/perfiles/index',[
                'titulo' => 'Perfiles',
                'perfiles'=>$perfiles,
                'alertas'=>$alertas,
                'opciones'=>$opciones            
            ]);            
        }
    }


    public static function opciones(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
       
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 

        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            header('Location: /admin/seguridad/perfiles');
        }       
        $perfil = Perfiles::find($id);
       
        if(!$perfil){
            header('Location: /admin/seguridad/perfiles');
        }          
        $opciones = Opciones::all('ASC');
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
      
            $data = json_decode(file_get_contents('php://input'), true);
            $seleccionados = $data['seleccionados'];
           
            // Lógica para guardar los IDs seleccionados en la base de datos
            if (isset($data['seleccionados']) && is_array($data['seleccionados'])) {
                foreach ($data['seleccionados'] as $item) {
                    // Asegúrate de que "id" existe en el $item
                    if (isset($item['id'])) {
                        $id = $item['id'];
                        // Realiza la inserción o actualización con $id
                    } else {
                        // Manejar el caso donde "id" no está presente
                        error_log("ID no encontrado en el item: " . json_encode($item)); // Registro de error
                    }
                }
            } else {
                error_log("No se encontraron elementos seleccionados o no son un array.");
            }
        
            //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            
            $perfil->sincronizar($_POST);

            $alertas = $perfil->validar();
           
            if(empty($alertas)){                
                
                $resultado = $perfil->guardar();            
                if($resultado){             
                    $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA'; 
                   //header('Location: /admin/logistica/productos');
                }
            }
            
        }

        //debuguear($opciones);
        // Mapa de categorías
        $valor = [$_GET['id']];                
        $accesos = Opciones::procedureLista('prc_ListaOpcionesPerfil',$valor); 
        $accesosPorId = [];
        foreach ($accesos as $acceso) {
            $accesosPorId[$acceso->id] = $acceso;
            $acceso->hijos = [];
        }

        // Construir la jerarquía
        $accesoRaiz = [];
        foreach ($accesos as $acceso) {
            if ($acceso->idsuperior === null) {
                $accesoRaiz[] = $acceso;
            } elseif (isset($accesosPorId[$acceso->idsuperior])) {
                // Solo agregar como hijo si el padre existe en el mapa
                $accesosPorId[$acceso->idsuperior]->hijos[] = $acceso;
            }
        }

    
        $router ->render('admin/seguridad/perfiles/opciones',[
            'titulo' => 'Asignar Opciones',
            'alertas' => $alertas,
            'perfil' => $perfil,
            'accesoRaiz'=>$accesoRaiz,
            'opciones'=>$opciones
        ]);
    }

      
    
}