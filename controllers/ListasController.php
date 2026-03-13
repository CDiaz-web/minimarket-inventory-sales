<?php

namespace Controllers;

use Model\Estados;
use Model\Listas;
use Model\Monedas;
use Model\Opciones;
use Model\Clientes;
use Model\Unidades;
use Model\ListaProductos;
use MVC\Router;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

require '../vendor/autoload.php';



class ListasController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $listas = Listas::where('idempresa',$_SESSION['idempresa'],false);
        $alertas = [];
        $router ->render('admin/mantenimiento/listas/index',[
                'titulo' => 'Lista de Precios',
                'listas'=>$listas,
                'alertas'=>$alertas,
                'opciones'=>$opciones        
            ]);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $lista = new Listas;  
        $estados = Estados::where('idmaster','3',false);
        $lista->idestado = 9; //  Activo por defecto  
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }     
            $busca = Listas::where('codigo', $_POST['codigo'],false);       
            $_POST['idempresa'] = $_SESSION['empresa'];
            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            //leer imagen      
         
            $lista->sincronizar($_POST);
            //validar
            $alertas = $lista->validar();
            if($busca){
                $alertas['error'][] = 'CODIGO YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar en la base de datos
                $resultado = $lista->guardar();
                if($resultado){
                    header('Location: /admin/mantenimiento/listas');
                }
            }
        }
        
      
        $router ->render('admin/mantenimiento/listas/crear',[
            'titulo' => 'Registrar Lista de Precios',
            'alertas' => $alertas,     
            'lista'=>$lista, 
            'estados'=>$estados,
            'opciones'=>$opciones        
  
        ]);
    }

    public static function editar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            header('Location: /admin/mantenimiento/listas');
        }       
        $lista = listas::find($id);   
        $estados = Estados::where('idmaster','3',false);
         
        if(!$lista){
            header('Location: /admin/mantenimiento/listas');
        }   

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            //agregamos informacion de auditoria al $_post
  
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            
            $lista->sincronizar($_POST);

            $alertas = $lista->validar();

            if(empty($alertas)){
                $resultado = $lista->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/mantenimiento/listas/editar',[
            'titulo' => 'Actualizar Lista de Precio',
            'alertas' => $alertas,       
            'lista'=>$lista,    
            'estados'=>$estados,
            'opciones'=>$opciones        
        ]);
    }

    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
           
            $lista = Listas::find($id);  
             
            $busca = Clientes::where('idlista', $id);

            if(!isset($lista)){
                header('Location: /admin/mantenimiento/listas');
            }
         
            
            if($busca){
                $alertas['error'][] = 'Existe clientes con la lista de precios que desea eliminar';
            }else{             
           
            $idempresa = $_SESSION['empresa'];
            //eliminamos lo anterior
            $valores = [$idempresa,$id];  
                $resultado = $lista->procedureMantenimiento('prm_elimina_lista_precios',$valores);        
                if($resultado){
                    header('Location: /admin/mantenimiento/listas'); 
                }    
            }
                $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
                $listas = Listas::where('idempresa',$_SESSION['empresa'],false);
                $router ->render('admin/mantenimiento/listas/index',[
                    'titulo' => 'Lista de Precios',
                    'listas'=>$listas,
                    'alertas'=>$alertas,
                    'opciones'=>$opciones        
                ]);

        }
    }

    public static function productosasignados(Router $router) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        $idLista = $_GET['idlista'] ?? null;
        $idEmpresa = $_SESSION['idempresa'];

        if (!$idLista || !$idEmpresa) {
            echo json_encode([]);
            return;
        }

        $valor = [$idEmpresa,$idLista];
        $resultado = ListaProductos::procedureLista(
            'prc_ListaProductosLista',
            $valor
        );

        echo json_encode($resultado);
    }

    public static function asignarproducto() {

        header('Content-Type: application/json');
   

        $data = json_decode(file_get_contents("php://input"), true);

        $idLista = $data['idlista'] ?? null;
        $idProducto  = $data['idproducto'] ?? null;
        $precio  = $data['precio'] ?? null;
        $user_crea = $_SESSION['id'] ?? null;

        if (!$idLista || !$idProducto || !$user_crea) {
            echo json_encode([
                'success' => false,
                'msg' => 'Datos incompletos o sesión inválida'
            ]);
            return;
        }

        $valores = [1, $idLista, $idProducto, $precio,$user_crea];

        try {
            ListaProductos::procedureMantenimiento(
                'prc_mantenimiento_asignaproducto',
                $valores
            );

            echo json_encode([
                'success' => true
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'msg' => $e->getMessage() // útil para depurar
            ]);
        }
    }


    public static function eliminarproducto() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $idLista = $data['idlista'] ?? null;
        $idProducto  = $data['idproducto'] ?? null;
        $user_crea = $_SESSION['id'] ?? null; // puede no usarse, pero validamos sesión

        if (!$idLista || !$idProducto || !$user_crea) {
            echo json_encode([
                'success' => false,
                'msg' => 'Datos incompletos o sesión inválida'
            ]);
            // return;
            exit;
        }

        // _tipo = 2 => DELETE
        $valores = [3, $idLista, $idProducto,0, $user_crea];

        try {
            ListaProductos::procedureMantenimiento(
                'prc_mantenimiento_asignaproducto',
                $valores
            );

            echo json_encode([
                'success' => true
            ]);
            exit;

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
            exit;
        }
    }

    public static function actualizaproducto() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        $idLista = $data['idlista'] ?? null;
        $idProducto  = $data['idproducto'] ?? null;
        $precio  = $data['precio'] ?? null;
        $user_crea = $_SESSION['id'] ?? null; // puede no usarse, pero validamos sesión

        if (!$idLista || !$idProducto || !$user_crea || $precio === null) {
            echo json_encode([
                'success' => false,
                'msg' => 'Datos incompletos o sesión inválida'
            ]);
            // return;
            exit;
        }

        // _tipo = 2 => actualiza
        $valores = [2, $idLista, $idProducto,$precio, $user_crea];

        try {
            ListaProductos::procedureMantenimiento(
                'prc_mantenimiento_asignaproducto',
                $valores
            );

            echo json_encode([
                'success' => true
            ]);
            exit;

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
            exit;
        }
    }


    public static function cargar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $categoria = new ListaProductos;
        $alertas = [];
        $id = $_POST['idlista'] ?? $_GET['id'] ?? null;

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }

            if (!$id) { $alertas['error'][] = 'No se recibió el ID de la lista de precios'; }
           
            if (isset($_FILES['fileProductos'])) {
                $file = $_FILES['fileProductos']['tmp_name'];
        
                // Cargar el archivo Excel
                $spreadsheet = IOFactory::load($file);
        
                // Obtener la primera hoja (puedes cambiarlo según tus necesidades)
                $sheet = $spreadsheet->getActiveSheet();
        
                // Obtener el iterador de filas, comenzando desde la fila 2 para omitir la primera (encabezados)
                $rowIterator = $sheet->getRowIterator(2);
                $nombresCampos = ['codigo', null, 'precio'];
                
                // Recorrer filas y celdas para insertar datos
                foreach ($rowIterator as $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false); // Para recorrer todas las celdas, incluso vacías
        
                    $datosFila = [];
                    $index = 0;
                    $datosFila['idempresa'] = $_SESSION['empresa'];  
                    $datosFila['idlista'] = (int)$id;
                    foreach ($cellIterator as $cell) {
                        $valorCelda = $cell->getValue();


                        // Asigna el valor de la celda al nombre del campo correspondiente
                        if (isset($nombresCampos[$index])) {
                            $datosFila[$nombresCampos[$index]] = $valorCelda;
                        }
                        $index++;
                    }                   
               
                    $datosFila['iduser'] = $_SESSION['id']; // Agregar el ID del usuario al array asociativo  
                     
                    if (
                        empty($datosFila['codigo']) &&
                        empty($datosFila['precio'])
                    ) {
                        break; // ← CLAVE
                    }                                
                    if (!empty($datosFila)) {
                       
                        $categoria->sincronizar($datosFila);   
                        $resultado = $categoria->procedureMantenimiento('prm_masivo_productos_lista',$datosFila);
                        if($resultado){             
                            $alertas['exito'][] = 'EL PROCESO SE REALIZO DE MANERA CORRECTA';
                        }
                        
                    }
                }
        
                // echo "Datos insertados correctamente desde el archivo Excel.";
            } else {
                echo "No se ha subido ningún archivo.";
            }

        }

        $router ->render('admin/mantenimiento/listas/carga_masiva/cargar',[
            'titulo' => 'Ingreso Masivo Productos Lista',
            'alertas'=>$alertas,
            'opciones'=>$opciones
       
        ]);
       
    }
    

}