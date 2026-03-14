<?php

namespace Controllers;
require '../vendor/autoload.php';

use Model\Estados;
use Model\Opciones;
use MVC\Router;
use Model\Perfiles;
use Model\Usuario;
use Model\UsuariosTiendas;

class UsuariosController {
    public static function index(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }  
        $alertas = [];     
        $valor = [$_SESSION['idempresa']];  
        // $productos = Productos::all('ASC');
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);  
        $usuarios = Usuario::procedureLista('prc_ListaUsuarios',$valor);
        $router ->render('admin/seguridad/usuarios/index',[
            'titulo' => 'Usuarios',
            'usuarios'=>$usuarios,
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
        $usuario = new Usuario;
        $perfiles = Perfiles::all('ASC');
        $estados = Estados::where('idmaster','3',false);
        $usuario->idestado = 9; //  Activo por defecto 
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
            $_POST['idempresa'] = $_SESSION['idempresa'];
    
       
            //leer imagen      
            $usuario->sincronizar($_POST);
            //validar
   
            $alertas = $usuario->validar();
            $alertas = $usuario->validar_cuenta();
            $existeUsuario = Usuario::where('email', $usuario->email,true);
            if($existeUsuario) {
                Usuario::setAlerta('error', 'El Usuario ya esta registrado');
                $alertas = Usuario::getAlertas();
            }
            //guardar el registro
            if(empty($alertas)){

                // Hashear el password
                $usuario->hashPassword();
         
                // Eliminar password2
                unset($usuario->password2);

                // Generar el Token
                $usuario->crearToken();

                // Crear un nuevo usuario
                $resultado =  $usuario->guardar();

                //guardar en la base de datos
                
                if($resultado){
                    header('Location: /admin/seguridad/usuarios');
                }
            }
        }
        
      
        $router ->render('admin/seguridad/usuarios/crear',[
            'titulo' => 'Registrar Usuario',
            'alertas' => $alertas,
            'usuario' => $usuario,
            'estados' => $estados,
            'perfiles'=>$perfiles,
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
            header('Location: /admin/seguridad/usuarios');
        }       
        $usuario = Usuario::find($id);
        if(!$usuario){
            header('Location: /admin/seguridad/usuarios');
        }   
        $perfiles = Perfiles::all('ASC');
        $estados = Estados::where('idmaster','3',false);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            
            //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            
            
            $validar = $usuario->password;

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar();
            $alertas = $usuario->validar_cuenta();
            if($_POST['password']!==$validar){
                $usuario->hashPassword();
            }
            if(empty($alertas)){                
                
                $resultado = $usuario->guardar();            
                if($resultado){             
                    $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA'; 
                   //header('Location: /admin/logistica/productos');
                }
            }
            
        }

        $router ->render('admin/seguridad/usuarios/editar',[
            'titulo' => 'Actualizar Usuario',
            'alertas' => $alertas,
            'usuario' => $usuario,
            'estados' => $estados,
            'perfiles'=>$perfiles,         
            'opciones'=>$opciones
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $valor = [$_SESSION['idempresa']];  
        $alertas = [];    
        // $productos = Productos::all('ASC');
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);  
        $usuarios = Usuario::procedureLista('prc_ListaUsuarios',$valor);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $usuario = Usuario::find($id);      
            $busca = UsuariosTiendas::where('idusuario', $id);
            if(!isset($usuario)){
                header('Location: /admin/seguridad/usuarios');
            }

            if($busca){
                $alertas['error'][] = 'el usuario tiene tiendas asignadas';
            }else{            
                $resultado = $usuario->eliminar();
            
                if($resultado){
                    header('Location: /admin/seguridad/usuarios'); 
                }   
            } 
            // Renderizamos la vista con las alertas
            $router->render('admin/seguridad/usuarios/index', [
                'titulo' => 'Usuarios',
                'alertas' => $alertas,    
                'usuarios'=>$usuarios,
                'opciones'=>$opciones
            ]);     
        }
    }

    public static function tiendasasignadas(Router $router) {

        header('Content-Type: application/json');

        $idUsuario = $_GET['idusuario'] ?? null;

        if (!$idUsuario) {
            echo json_encode([]);
            return;
        }

        // El SP espera parámetros, normalmente un array
        $valor = [$idUsuario];

        $resultado = UsuariosTiendas::procedureLista(
            'prc_ListaTiendasUsuario',
            $valor
        );

        echo json_encode($resultado);
    }

    public static function asignartienda() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $idUsuario = $data['idusuario'] ?? null;
        $idTienda  = $data['idtienda'] ?? null;
        $user_crea = $_SESSION['id'] ?? null;

        if (!$idUsuario || !$idTienda || !$user_crea) {
            echo json_encode([
                'success' => false,
                'msg' => 'Datos incompletos o sesión inválida'
            ]);
            return;
        }

        $valores = [1, $idUsuario, $idTienda, $user_crea];

        try {
            UsuariosTiendas::procedureMantenimiento(
                'prc_mantenimiento_asignatienda',
                $valores
            );

            echo json_encode([
                'success' => true
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'msg' => $e->getMessage() // útil para depurar
            ]);
        }
    }

    public static function eliminartienda() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $idUsuario = $data['idusuario'] ?? null;
        $idTienda  = $data['idtienda'] ?? null;
        $user_crea = $_SESSION['id'] ?? null; // puede no usarse, pero validamos sesión

        if (!$idUsuario || !$idTienda || !$user_crea) {
            echo json_encode([
                'success' => false,
                'msg' => 'Datos incompletos o sesión inválida'
            ]);
            // return;
            exit;
        }

        // _tipo = 2 => DELETE
        $valores = [2, $idUsuario, $idTienda, $user_crea];

        try {
            UsuariosTiendas::procedureMantenimiento(
                'prc_mantenimiento_asignatienda',
                $valores
            );

            echo json_encode([
                'success' => true
            ]);
            exit;

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
            exit;
        }
    }


}