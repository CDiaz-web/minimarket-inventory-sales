<?php

namespace Controllers;

use Model\Dashboard;
use Model\Opciones;
use Model\Productos;
use MVC\Router;


class DashboardCarsController {

    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        
        //obtener ultimos registros
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
                
        $valor = [$_SESSION['idempresa'],$_SESSION['idtienda']];      
       
        $cards = Dashboard::procedure('prd_dashboard_cards',$valor);          

        $productostop = Productos::procedureLista('prd_dashboard_tablas',[1,$_SESSION['idempresa'],$_SESSION['idtienda']]);

        $productosdown = Productos::procedureLista('prd_dashboard_tablas',[2,$_SESSION['idempresa'],$_SESSION['idtienda']]);
             
        $simbolo_moneda = $_SESSION['simbolo_moneda'];
    
        $router ->render('admin/dashboard/index',[
                'titulo' => 'Panel de Administracion',
                'cards' =>$cards,
                'productostop' =>$productostop,
                'productosdown' =>$productosdown,
                'simbolo_moneda'=>$simbolo_moneda,
                'opciones'=>  $opciones
            ]);
    }
}