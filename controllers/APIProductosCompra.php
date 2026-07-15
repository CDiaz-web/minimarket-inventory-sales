<?php

namespace Controllers;

use Model\Productos;


class APIProductosCompra{

    public static function productosporcompra(){

        if(is_auth()){          
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $idempresa = $_SESSION['idempresa'] ;
            $idtienda = $_SESSION['idtienda'] ;
            $valor = [$idempresa,$idtienda];  
            
        
            $productos = Productos::procedureLista('prc_productos_tienda_lista',$valor);
            echo json_encode($productos, JSON_UNESCAPED_SLASHES);

        }



    }


}
