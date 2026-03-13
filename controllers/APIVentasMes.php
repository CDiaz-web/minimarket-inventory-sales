<?php

namespace Controllers;

use Model\OrdenVenta;

class APIVentasMes{
    public static function ventasmes(){
        if(is_auth()){ 
            $valor = [$_SESSION['idtienda']]; 
            $mes = OrdenVenta::procedureLista('prc_ObtenerVentasMensual',$valor); 
         
            echo json_encode($mes, JSON_UNESCAPED_SLASHES);
        }
    }

}