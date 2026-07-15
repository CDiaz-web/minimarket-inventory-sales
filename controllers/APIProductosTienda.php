<?php

namespace Controllers;

use Model\Productos;


class APIProductosTienda{

    public static function productosportienda(){

        if (session_status() === PHP_SESSION_NONE) {
        session_start();
        }
        $empresa  = $_SESSION['idempresa'];
        $idTienda = $_SESSION['idtienda'] ;       

        $valor = [$empresa,$idTienda];    

        $productos = Productos::procedureLista('prc_productos_tienda',$valor);
        echo json_encode($productos, JSON_UNESCAPED_SLASHES);
    }


}