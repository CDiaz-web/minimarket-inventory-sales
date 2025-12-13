<?php

namespace Controllers;

use Model\PerfilOpciones;
use MVC\Router;

class APIOpciones{
    public static function guardaropciones(Router $router){  

        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
       
            if(!is_admin()){
                header('Location: /login');
            } 

            //eliminamos las opciones ya asiganadas
            
            $opciones = new PerfilOpciones;
  
            $data = json_decode(file_get_contents('php://input'), true);

            $idperfil = $data['opcion'];
            $iduser = $_SESSION['id'];
            //eliminamos lo anterior
            $valores = ["1",0,$idperfil,$iduser];              
            $resultado = $opciones->procedureMantenimiento('prm_OpcionesPerfil',$valores);

            

            // Lógica para guardar los IDs seleccionados en la base de datos
            
            if (isset($data['seleccionados']) && is_array($data['seleccionados'])) {
                
                foreach ($data['seleccionados'] as $id) {                    
                    // Asegúrate de que "id" existe en el $item
                    if (isset($id)) {
                        $valores = ["2",$id,$idperfil,$iduser];  
                        $resultado = $opciones->procedureMantenimiento('prm_OpcionesPerfil',$valores);                                            
                    } else {
                        // Manejar el caso donde "id" no está presente
                        error_log("ID no encontrado en el item: " . json_encode($id)); // Registro de error
                    }
                }
                if($resultado){             
                    //$alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA'; 
                    $cadena = 'Location: /admin/seguridad/perfiles';
                   
                    header('Location: /admin/seguridad/perfiles');
                    header($cadena);                     
                }
            } else {
                error_log("No se encontraron elementos seleccionados o no son un array.");
                $alertas['error'][] = 'ERROR AL GUARDAR'; 
            }  
            
        }


    }     

}