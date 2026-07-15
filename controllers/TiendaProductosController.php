<?php

namespace Controllers;
require '../vendor/autoload.php';
use Model\Opciones;
use MVC\Router;
use Model\TiendaProductos;

class TiendaProductosController {
    public static function index(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }       

        // $productos = Productos::all('ASC');
   
        $valor = [$_SESSION['idtienda'] ,$_SESSION['idempresa'],0]; 
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $tiendaproductos = TiendaProductos::procedureLista('prc_tienda_productos_lista',$valor);
        $router ->render('admin/gestion/inventarios/tiendaproductos/index',[
            'titulo' => 'Stock Por Tienda',        
            'tiendaproductos'=>$tiendaproductos,
            'opciones'=>$opciones            
        ]);
    }
    

    public static function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recibimos JSON
            $data = json_decode(file_get_contents("php://input"), true);

            $id        = $data['id'] ?? null;
            $stockMin  = $data['stock_min'] ?? null;
            $stockMax  = $data['stock_max'] ?? null;

            if ($id && $stockMin !== null && $stockMax !== null) {
                // Buscar el producto
                $producto = TiendaProductos::find($id);

                if ($producto) {
                    $producto->stock_min = $stockMin;
                    $producto->stock_max = $stockMax;

                    $resultado = $producto->guardar();

                    echo json_encode([
                        "success" => $resultado,
                        "mensaje" => $resultado ? "Stock actualizado correctamente" : "Error al guardar en BD"
                    ]);
                } else {
                    echo json_encode([
                        "success" => false,
                        "mensaje" => "Producto no encontrado"
                    ]);
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "mensaje" => "Datos incompletos"
                ]);
            }
            return;
        }
    }


     
}