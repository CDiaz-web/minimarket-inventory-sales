<?php

namespace Controllers;

use Model\TipoPago;

class APITipoPago{

    public static function tipopago(){
        if(is_auth()){    

           $empresa  = $_SESSION['idempresa'];   
           $tipospago = TipoPago::findArray(['idempresa'=>$empresa,'activo'=> 1],false) ?? [];
         
            echo json_encode($tipospago, JSON_UNESCAPED_SLASHES);
        }

    }

}