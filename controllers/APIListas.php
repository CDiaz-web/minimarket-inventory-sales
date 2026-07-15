<?php

namespace Controllers;

use Model\Listas;


class APIListas{

    public static function listas(){
        if(is_auth()){          

            $empresa  = $_SESSION['idempresa'];      
            $listas = listas::findArray(['idempresa'=>$empresa,'activo'=> 1],false) ?? [];
                     
            echo json_encode($listas, JSON_UNESCAPED_SLASHES);
        }

    }

}
