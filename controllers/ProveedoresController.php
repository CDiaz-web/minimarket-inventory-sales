<?php

namespace Controllers;

use MVC\Router;
use Model\Proveedores;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Model\Opciones;


require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


class ProveedoresController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $alertas = [];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);       
        $proveedores = Proveedores::where('idempresa',$_SESSION['idempresa'],false);      
        $router ->render('admin/mantenimiento/proveedores/index',[
                'titulo' => 'Proveedores',
                'alertas' => $alertas,
                'proveedores'=>$proveedores,
                'opciones'=>$opciones        
            ]);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $valor = $_SESSION['idempresa'];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $proveedor = new Proveedores();  

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

           $busca = Proveedores::where('documento', $_POST['documento'],false);

           if  ($_POST['tipo_persona'] === 'N'){
                $_POST['nombre_proveedor']= trim($_POST['nombre'] . " " . $_POST['apellidos']);
           }else{
                $_POST['nombre_proveedor']= $_POST['razon_social'];
           }

            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;  
            
            $proveedor->sincronizar($_POST);
            //validar

            $alertas = $proveedor->validar();
            if($busca){
                $alertas['error'][] = 'PROVEEDOR YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){

                //guardar en la base de datos
                $resultado = $proveedor->guardar();
                if($resultado){
                    header('Location: /admin/mantenimiento/proveedores');
                }
            }
        }
        
      
        $router ->render('admin/mantenimiento/proveedores/crear',[
            'titulo' => 'Registrar Proveedor',
            'alertas' => $alertas,     
            'proveedor'=>$proveedor,
            'opciones'=>$opciones       
  
        ]);
    }

    public static function editar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            header('Location: /admin/mantenimiento/proveedores');
        }    
        $valor = $_SESSION['idempresa'];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);    
        $proveedor = Proveedores::find($id);        

        if(!$proveedor){
            header('Location: /admin/mantenimiento/proveedores');
        }   
       
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //agregamos informacion de auditoria al $_post
           if  ($_POST['tipo_persona'] === 'N'){
                $_POST['nombre_proveedor']= trim($_POST['nombre'] . " " . $_POST['apellidos']);
           }else{
                $_POST['nombre_proveedor']= $_POST['razon_social'];
           }
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;
            $proveedor->sincronizar($_POST);

            $alertas = $proveedor->validar();

            if(empty($alertas)){
                //guardar las imagenes

                $resultado = $proveedor->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/mantenimiento/proveedores/editar',[
            'titulo' => 'Actualizar Proveedor',
            'alertas' => $alertas,     
            'proveedor'=>$proveedor,
            'opciones'=>$opciones       
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $proveedores = Proveedores::where('idempresa',$_SESSION['idempresa'],false);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $proveedor = Proveedores::find($id);     
            
            if(!isset($proveedor)){
                header('Location: /admin/mantenimiento/proveedores');
            }

                $resultado = $proveedor->eliminar();
            
                if($resultado){
                    header('Location: /admin/mantenimiento/proveedores'); 
                } 
            // }

            // Renderizamos la vista con las alertas
            $router->render('admin/mantenimiento/proveedores/index', [
                'titulo' => 'Proveedor',
                'alertas' => $alertas,
                'proveedores'=>$proveedores,
                'opciones'=>$opciones  
            ]);            
      
        }
    }

    

    public static function traerDocumento() {
        $tipo = $_GET['tipo'] ?? null;
        $numero = $_GET['numero'] ?? null;

        if (!$tipo || !$numero) {
            echo json_encode(["error" => "Faltan parámetros"]);
            return;
        }

        // Token de ApisPeru (coloca tu token aquí)
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImNkaWF6cjRAZ21haWwuY29tIn0.Canxbv-mrc_ztARuWr63TLDBYW-sP-1q2YyYg_kEHKw";

    if ($tipo === "dni") {
        $url = "https://dniruc.apisperu.com/api/v1/dni/$numero?token=$token";
    } else { // ruc
        $url = "https://dniruc.apisperu.com/api/v1/ruc/$numero?token=$token";
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 10
    ]);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["error" => "Error al consultar el servicio"]);
        curl_close($ch);
        return;
    }
    curl_close($ch);

    $data = json_decode($response, true);
    if (!$data) {
        echo json_encode(["error" => "No se encontró información"]);
        return;
    }

    // Si es DNI, devolvemos lo esperado
    if ($tipo === "dni") {
        echo json_encode([
            "nombres" => $data['nombres'] ?? "",
            "apellidoPaterno" => $data['apellidoPaterno'] ?? "",
            "apellidoMaterno" => $data['apellidoMaterno'] ?? ""
        ]);
        return;
    }

    // --- RUC: heurística para distinguir PERSONA NATURAL vs JURIDICA ---
    $razon = trim($data['razonSocial'] ?? "");
    $prefijo = substr($numero, 0, 2);

    // si la API trae 'tipo' y dice explícitamente PERSONA NATURAL, respetarlo
    $isNatural = false;
    if (isset($data['tipo']) && stripos($data['tipo'], 'PERSONA NATURAL') !== false) {
        $isNatural = true;
    }

    // regla por prefijo de RUC (10,15,16,17 => persona natural)
    if (!$isNatural && preg_match('/^(10|15|16|17)/', $numero)) {
        $isNatural = true;
    }

    // heurística por contenido: si no parece nombre de empresa (sin marcas jurídicas)
    if (!$isNatural) {
        $companyMarkers = ['S.A','S.A.C','SAC','SRL','S.R.L','E.I.R.L','EIRL','S.A.A','SAA','SAS','SOCIEDAD','CÍA','CIA','COMERCIAL','LIMITADA'];
        $foundMarker = false;
        foreach ($companyMarkers as $m) {
            if (stripos($razon, $m) !== false) { $foundMarker = true; break; }
        }
        $tokens = preg_split('/\s+/', $razon, -1, PREG_SPLIT_NO_EMPTY);
        if (!$foundMarker && count($tokens) >= 3) {
            // ejemplo: "ACHALMA MORENO DINA MERIDA" -> apellidos + apellidos + nombres
            $isNatural = true;
        }
    }

    if ($isNatural) {
        // parsear razonSocial en apellidos y nombres (heurística simple)
        $tokens = preg_split('/\s+/', $razon, -1, PREG_SPLIT_NO_EMPTY);
        $apellidoP = $tokens[0] ?? "";
        $apellidoM = $tokens[1] ?? "";
        $nombres = count($tokens) > 2 ? implode(' ', array_slice($tokens, 2)) : ($tokens[1] ?? "");

        echo json_encode([
            "tipo" => "N",
            "razonSocial" => $razon,
            "nombres" => $nombres,
            "apellidoPaterno" => $apellidoP,
            "apellidoMaterno" => $apellidoM,
            "direccion" => $data['direccion'] ?? ""
        ]);
    } else {
        // es empresa / juridico
        echo json_encode([
            "tipo" => "J",
            "razonSocial" => $razon,
            "direccion" => $data['direccion'] ?? ""
        ]);
    }
}

}