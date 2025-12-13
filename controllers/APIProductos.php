<?php

namespace Controllers;

use Model\Productos;

class APIProductos{
    public static function productos(){
        if(is_auth()){ 
            $valor = [$_SESSION['idtienda'],1]; 
            $productos = Productos::procedureLista('prc_ListaProductosTienda',$valor);        
 
           
           $articulos = [];
           foreach ($productos as $resultado) {
               $articulos[] = [
                   'buscar' => $resultado->codigo . ' ' . $resultado->nombre ,
                   'codigo' => $resultado->codigo,
                   'label' => $resultado->nombre,
                   'id' => $resultado->id,
                   'venta' => $resultado->venta,
                   'stock' => $resultado->stock_actual,                   
                   'unidad' => $resultado->unidad
               ];
           }
           
           echo json_encode($articulos, JSON_UNESCAPED_SLASHES);
        }
    }

}