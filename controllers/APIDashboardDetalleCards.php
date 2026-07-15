<?php

namespace Controllers;

use Model\Productos;

class APIDashboardDetalleCards{
    public static function listaventasdeldia(){
        if(is_auth()){ 
           $valor = [1,$_SESSION['idempresa'],$_SESSION['idtienda']]; 
           $productos = Productos::procedureLista('prd_dashboard_detalle_cards',$valor); 
           $articulos = [];
           foreach ($productos as $resultado) {
               $articulos[] = [
                   'codigo' => $resultado->codigo,
                   'nombre' => $resultado->nombre,
                   'cantidad' => $resultado->cantidad,
                   'precio' => $resultado->venta,
                   'total' => $resultado->total
               ];
           }
           
           echo json_encode($articulos, JSON_UNESCAPED_SLASHES);
        }
    }
    public static function listaventasdelmes(){
        if(is_auth()){ 
           $valor = [2,$_SESSION['idempresa'],$_SESSION['idtienda']]; 
           $productos = Productos::procedureLista('prd_dashboard_detalle_cards',$valor); 
           $articulos = [];
           foreach ($productos as $resultado) {
               $articulos[] = [
                   'codigo' => $resultado->codigo,
                   'nombre' => $resultado->nombre,
                   'cantidad' => $resultado->cantidad,
                   'precio' => $resultado->costo,
                   'total' => $resultado->total
               ];
           }
           
           echo json_encode($articulos, JSON_UNESCAPED_SLASHES);
        }
    }
    public static function listaproductostienda(){
        if(is_auth()){ 
           $valor = [4,$_SESSION['idempresa'],$_SESSION['idtienda']]; 
           $productos = Productos::procedureLista('prd_dashboard_detalle_cards',$valor); 
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
    public static function listapocostock(){
        if(is_auth()){ 
           $valor = [5,$_SESSION['idempresa'],$_SESSION['idtienda']]; 
           $productos = Productos::procedureLista('prd_dashboard_detalle_cards',$valor); 
           $articulos = [];
           foreach ($productos as $resultado) {
               $articulos[] = [
                   'codigo' => $resultado->codigo,
                   'nombre' => $resultado->nombre,
                   'stock' => $resultado->stock_actual,
                   'stock_min' => $resultado->stock_min
               ];
           }
           
           echo json_encode($articulos, JSON_UNESCAPED_SLASHES);
        }
    }
    public static function listacomprasmes(){
        if(is_auth()){ 
           $valor = [6,$_SESSION['idempresa'],$_SESSION['idtienda']]; 
           $productos = Productos::procedureLista('prd_dashboard_detalle_cards',$valor); 
           $articulos = [];
           foreach ($productos as $resultado) {
               $articulos[] = [
                   'codigo' => $resultado->codigo,
                   'nombre' => $resultado->nombre,
                   'cantidad' => $resultado->cantidad,
                   'costo' => $resultado->costo,
                   'total' => $resultado->total
               ];
           }
           
           echo json_encode($articulos, JSON_UNESCAPED_SLASHES);
        }
    }

}