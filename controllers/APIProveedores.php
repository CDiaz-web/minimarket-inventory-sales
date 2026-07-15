<?php

namespace Controllers;

use Model\Proveedores;


class APIProveedores{

    public static function proveedores(){
        if(is_auth()){          

            $empresa  = $_SESSION['idempresa'];
            $proveedores = Proveedores::findArray(['idempresa'=>$empresa,'activo'=> 1],false) ?? [];
         
            echo json_encode($proveedores, JSON_UNESCAPED_SLASHES);
        }

    }

}




