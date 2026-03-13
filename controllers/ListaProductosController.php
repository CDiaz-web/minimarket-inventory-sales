<?php

namespace Controllers;
require '../vendor/autoload.php';

use MVC\Router;
use Model\Opciones;
use Model\Productos;
use Model\UsuariosTiendas;
use Model\ListaProductos;

class ListaProductosController {

     public static function crear(Router $router){
         if(!is_admin()){
             header('Location: /login');
         }
         $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
         $alertas = [];
         $idlista = $_GET['id'];
         $productos = Productos::where('idestado',7);
         $lista_productos = new ListaProductos;
        
         if($_SERVER['REQUEST_METHOD'] === 'POST'){
             if(!is_admin()){
                 header('Location: /login');
             }
             
             $idproducto = $_POST['idproducto'];
             
             $busca_productos = ListaProductos::findArray(['idlista'=> $idlista,'idproducto'=> $idproducto],false) ?? [];
   
             // //agregamos informacion de auditoria al $_post
             date_default_timezone_set('America/Lima');
             $_POST['idlista']=$idlista ;        
             $_POST['idusercrea']=$_SESSION['id'];
             $_POST['fechacrea']=date("Y-m-d H:i:s");            
             $_POST['idusermodi']=$_SESSION['id'];
             $_POST['fechamodi']=date("Y-m-d H:i:s");     
    
             //leer imagen    
         
             $lista_productos->sincronizar($_POST);
             
             //validar
           
             $alertas = $lista_productos->validar();
             if($busca_productos ) {
                 ListaProductos::setAlerta('error', 'El Producto ya fue registrado');
                 $alertas = ListaProductos::getAlertas();
             }
             //guardar el registro
             if(empty($alertas)){
                 //guardar las imagenes

                 //guardar en la base de datos
                 $resultado = $lista_productos->guardar();
                 if($resultado){
                     header('Location: /admin/mantenimiento/listas/lista_productos?id=' . $idlista);
                 }
             }
         }        
      
         $router ->render('admin/mantenimiento/listas/lista_productos/crear',[
             'titulo' => 'Registrar Producto',
             'alertas' => $alertas,       
             'productos'=>$productos,
             'opciones'=>$opciones
         ]);
     }

    
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
    
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $idlista = $_POST['id'];               
            $producto_lista = ListaProductos::find($idlista);    
           // $user = $$usuario_tienda->idusuario;
            if(!isset($producto_lista)){
           
                header('Location: /admin/mantenimiento/listas/lista_productos');                 
            }
         
           $resultado = $usuario_tienda->eliminar($idtienda);
       
            if($resultado){
             
               $cadena = 'Location: /admin/seguridad/usuarios/tiendas?id='  .  $usuario_tienda->idusuario;
               //debuguear($cadena);
               header($cadena); 
            }       
        }
    }
    
}