<?php

namespace Controllers;

use MVC\Router;
use Model\Clientes;
use Model\Listas;
use PhpOffice\PhpSpreadsheet\Shared\Date;
// use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use Model\Opciones;
use Model\Productos;
use Model\Tiendas;
use Model\TipoCliente;

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


class ClientesController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $alertas = [];
        $valor = [$_SESSION['idempresa']];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);       
        $clientes = Clientes::procedureLista('prc_ListaClientes',$valor);
        $router ->render('admin/mantenimiento/clientes/clientes/index',[
                'titulo' => 'Clientes',
                'alertas' => $alertas,
                'clientes'=>$clientes,
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
        $cliente = new Clientes();            
        $tipos = TipoCliente::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];        
        $tiendas = Tiendas::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];         
        $listas = Listas::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
        $cliente->idtienda_default = $_SESSION['idtienda'];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

           $busca = Clientes::where('documento', $_POST['documento'],false);

           if  ($_POST['tipo_persona'] === 'N'){
                $_POST['nombre_cliente']= trim($_POST['nombre'] . " " . $_POST['apellidos']);
           }else{
                $_POST['nombre_cliente']= $_POST['razon_social'];
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
            
            $cliente->sincronizar($_POST);
            //validar

            $alertas = $cliente->validar();
            if($busca){
                $alertas['error'][] = 'CLIENTE YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){

                //guardar en la base de datos
                $resultado = $cliente->guardar();
                if($resultado){
                    header('Location: /admin/mantenimiento/clientes/clientes');
                }
            }
        }
        
      
        $router ->render('admin/mantenimiento/clientes/clientes/crear',[
            'titulo' => 'Registrar Cliente',
            'alertas' => $alertas,     
            'cliente'=>$cliente,
            'tipos'=>$tipos,
            'tiendas'=>$tiendas,       
            'listas'=>$listas,
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
            header('Location: /admin/mantenimiento/clientes');
        }    
        $valor = $_SESSION['idempresa'];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);    
        $cliente = Clientes::find($id);        
        $tipos = TipoCliente::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
        $tiendas = Tiendas::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
        $listas = Listas::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
        if(!$cliente){
            header('Location: /admin/mantenimiento/clientes/clientes');
        }   
       
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //agregamos informacion de auditoria al $_post
           if  ($_POST['tipo_persona'] === 'N'){
                $_POST['nombre_cliente']= trim($_POST['nombre'] . " " . $_POST['apellidos']);
           }else{
                $_POST['nombre_cliente']= $_POST['razon_social'];
           }
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;
            $cliente->sincronizar($_POST);

            $alertas = $cliente->validar();

            if(empty($alertas)){
                //guardar las imagenes

                $resultado = $cliente->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
              
                }
            }
            
        }

        $router ->render('admin/mantenimiento/clientes/clientes/editar',[
            'titulo' => 'Actualizar Cliente',
            'alertas' => $alertas,     
            'cliente'=>$cliente,
            'tipos'=>$tipos,
            'tiendas'=>$tiendas,
            'listas'=>$listas,   
            'opciones'=>$opciones       
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $clientes = Clientes::where('idempresa',$_SESSION['idempresa'],false);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $cliente = Clientes::find($id);      

            // $busca = Productos::where('idcategoria', $id);
            
            if(!isset($cliente)){
                header('Location: /admin/mantenimiento/cliente/cliente');
            }
            // if($busca){
            //     $alertas['error'][] = 'EL CLIENTE NO PUEDE SER ELIMINADO';
            // }else{
                $resultado = $cliente->eliminar();
            
                if($resultado){
                    header('Location: /admin/mantenimiento/clientes/clientes'); 
                } 
            // }

            // Renderizamos la vista con las alertas
            $router->render('admin/mantenimiento/clientes/clientes/index', [
                'titulo' => 'Clientes',
                'alertas' => $alertas,
                'clientes'=>$clientes,
                'opciones'=>$opciones  
            ]);            
      
        }
    }

    public static function cargar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $cliente = new Clientes;
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }
           
            if (isset($_FILES['fileProductos'])) {
                $file = $_FILES['fileProductos']['tmp_name'];
        
                // Cargar el archivo Excel
                $spreadsheet = IOFactory::load($file);
        
                // Obtener la primera hoja (puedes cambiarlo según tus necesidades)
                $sheet = $spreadsheet->getActiveSheet();
        
                // Obtener el iterador de filas, comenzando desde la fila 2 para omitir la primera (encabezados)
                $rowIterator = $sheet->getRowIterator(2);
                $nombresCampos = ['documento','nombre','apellido','razon_social','categoria','telefono','direccion','tienda'];
                date_default_timezone_set('America/Lima');
                // Recorrer filas y celdas para insertar datos
                foreach ($rowIterator as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // Para recorrer todas las celdas, incluso vacías
        
                    $datosFila = [];
                    $index = 0;
                    foreach ($cellIterator as $cell) {
                        $valorCelda = $cell->getValue();
                        // Verificar si el valor es una fecha (número serial)
                        if ($cell->getDataType() === \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC && Date::isDateTime($cell)) {
                            // Convertir el valor a un objeto DateTime y luego a un formato de fecha legible
                            $fecha = Date::excelToDateTimeObject($valorCelda);
                            $valorCelda = $fecha->format('Y-m-d'); // Cambia el formato según necesites
                        }

                        // Asigna el valor de la celda al nombre del campo correspondiente
                        if (isset($nombresCampos[$index])) {
                            $datosFila[$nombresCampos[$index]] = $valorCelda;
                        }
                        $index++;
                    } 
                    // datos para pasar la validacion    
                    $datosFila['idtienda_default'] = $_SESSION['idtienda'];  
                    $datosFila['idtipo'] = 8; 
                    $datosFila['tipo_persona'] = 'N'; 
                    //fin
                    $datosFila['idestado'] = '9'; 
                    $datosFila['idusercrea'] = $_SESSION['id']; // Agregar el ID del usuario al array asociativo  
                    $datosFila['idempresa'] = $_SESSION['idempresa'];   
                    
                    // debuguear($datosFila);
                    if (!empty($datosFila)) {
                        
                        $cliente->sincronizar($datosFila);                     

                        $alertas = $cliente->validar();
                        
                        if(empty($alertas)){                           
                            $resultado = $cliente->procedureMantenimiento('prm_clientes_masivo',$datosFila);
                            if($resultado){             
                                $alertas['exito'][] = 'EL PROCESO SE REALIZO DE MANERA CORRECTA';
                            }
                        }
                    }
                }
        
                // echo "Datos insertados correctamente desde el archivo Excel.";
            } else {
                echo "No se ha subido ningún archivo.";
            }

        }

        $router ->render('admin/mantenimiento/clientes/clientes/cargar',[
            'titulo' => 'Ingreso Masivo Clientes',
            'alertas'=>$alertas,
            'opciones'=>$opciones
       
        ]);
       
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