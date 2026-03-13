<?php

namespace Controllers;

use Model\Dashboard;
use Model\Opciones;
use MVC\Router;


class DashboardController {


    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        
        //obtener ultimos registros
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
                
        $valor = [$_SESSION['idtienda']];      
       
        $cards = Dashboard::procedure('prc_ObtenerDatosDashboard',$valor);  
             
        $simbolo_moneda = $_SESSION['simbolo_moneda'];
    
        $router ->render('admin/dashboard/index',[
                'titulo' => 'Panel de Administracion',
                'cards' =>$cards,
                'simbolo_moneda'=>$simbolo_moneda,
                'opciones'=>  $opciones
            ]);
    }
}