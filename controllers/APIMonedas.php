<?php

namespace Controllers;

use Model\Monedas;

class APIMonedas{

    public static function monedas(){
        if(is_auth()){         

           
            $monedas = Monedas::all('ASC');
         
            echo json_encode($monedas, JSON_UNESCAPED_SLASHES);
        }

    }

}