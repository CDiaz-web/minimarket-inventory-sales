<?php

namespace Controllers;

use Model\TipoPago;

class APITipoPago{

    public static function tipopago(){
        if(is_auth()){    
           
            $tipospago = TipoPago::all('ASC');
         
            echo json_encode($tipospago, JSON_UNESCAPED_SLASHES);
        }

    }

}