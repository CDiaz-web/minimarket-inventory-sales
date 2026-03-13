<?php

namespace Controllers;

use Model\Productos;


class APIproductosTiendaLista{

    public static function productosporlista(){

        if (session_status() === PHP_SESSION_NONE) {
        session_start();
        }
        $idTienda = $_SESSION['idtienda'] ;
        $idLista  = $_GET['idlista'];

        $valor = [$idTienda,$idLista];  
        
    
        $productos = Productos::procedureLista('prc_productos_lista_tienda',$valor);
        echo json_encode($productos, JSON_UNESCAPED_SLASHES);
    }


}
