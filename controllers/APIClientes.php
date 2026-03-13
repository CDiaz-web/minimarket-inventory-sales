<?php

namespace Controllers;

use Model\Clientes;


class APIClientes{

    public static function clientes(){
        if(is_auth()){          

            $empresa  = $_SESSION['idempresa'];
            $clientes = clientes::where('idempresa',$empresa);
         
            echo json_encode($clientes, JSON_UNESCAPED_SLASHES);
        }

    }

}


