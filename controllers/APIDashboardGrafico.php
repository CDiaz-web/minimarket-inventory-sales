<?php

namespace Controllers;

use Model\OrdenVenta;

class APIDashboardGrafico{
    public static function dashboardgrafico(){
        if(is_auth()){ 
            $valor = [$_SESSION['idtienda'],$_SESSION['idempresa']]; 
            $mes = OrdenVenta::procedureLista('prd_dashboard_grafico_ventas',$valor); 
         
            echo json_encode($mes, JSON_UNESCAPED_SLASHES);
        }
    }

}