<?php

namespace Controllers;

use Model\Productos;

class APITotalProductosTienda{
    public static function productos(){
        if(is_auth()){ 
            $valor = [$_SESSION['idtienda'],0]; 
            $productos = Productos::procedureLista('prc_ListaProductosTienda',$valor);        
 
           
           $articulos = [];
           foreach ($productos as $resultado) {
               $articulos[] = [
                   'codigo' => $resultado->codigo,
                   'nombre' => $resultado->nombre,
                   'venta' => $resultado->venta,
                   'stock' => $resultado->stock_actual,
                   'stock_minimo' => $resultado->stock_min,
                   'stock_maximo' => $resultado->stock_max
               ];
           }
           
           echo json_encode($articulos, JSON_UNESCAPED_SLASHES);
        }
    }

}