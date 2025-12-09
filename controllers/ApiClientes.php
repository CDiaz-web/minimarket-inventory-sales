<?php

namespace Controllers;

use Model\clientes;


class APIClientes{

    public static function clientes(){
        if(is_auth()){          

           
            $clientes = clientes::where('idempresa','1');
         
            echo json_encode($clientes, JSON_UNESCAPED_SLASHES);
        }

    }

}


