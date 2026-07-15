<?php

namespace Controllers;

use MVC\Router;
use Model\Categorias;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use Model\Opciones;
use Model\Productos;

require '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;


class CategoriasController {
    
    public static function index(Router $router){
        if(!is_auth()){
             header('Location: /login');
             return;
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $categorias = Categorias::where('idempresa',$_SESSION['idempresa'],false);

        $router ->render('admin/mantenimiento/productos/categorias/index',[
                'titulo' => 'Categorias',
                'alertas' => $alertas,
                'categorias'=>$categorias,
                'opciones'=>$opciones        
            ]);
    }

    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $categoria = new Categorias;
  
  
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

           $busca = Categorias::where('codigo', $_POST['codigo'],false);

            // //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusercrea']=$_SESSION['id'];
            $_POST['fechacrea']=date("Y-m-d H:i:s");
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;
            //leer imagen      
            
            $categoria->sincronizar($_POST);
            //validar

            $alertas = $categoria->validar();
            if($busca){
                $alertas['error'][] = 'CODIGO YA REGISTRADO';
            }
            //guardar el registro
            if(empty($alertas)){

                //guardar en la base de datos
                $resultado = $categoria->guardar();
                if($resultado){
                    header('Location: /admin/mantenimiento/productos/categorias');
                }
            }
        }
        
      
        $router ->render('admin/mantenimiento/productos/categorias/crear',[
            'titulo' => 'Registrar Categoria',
            'alertas' => $alertas,     
            'categoria'=>$categoria,
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
            header('Location: /admin/mantenimiento/productos/categorias');
        }    
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']);    
        $categoria = Categorias::find($id);
  
        if(!$categoria){
            header('Location: /admin/mantenimiento/productos/categorias');
        }   
       
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //agregamos informacion de auditoria al $_post

            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;
            $categoria->sincronizar($_POST);

            $alertas = $categoria->validar();

            if(empty($alertas)){
                //guardar las imagenes

                $resultado = $categoria->guardar();
            
                if($resultado){      
                   $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA';       
                   //header('Location: /admin/logistica/categorias');
                }
            }
            
        }

        $router ->render('admin/mantenimiento/productos/categorias/editar',[
            'titulo' => 'Actualizar Categoria',
            'alertas' => $alertas,       
            'categoria'=>$categoria,           
            'opciones'=>$opciones
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $categorias = Categorias::where('idempresa',$_SESSION['idempresa'],false);
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $categoria = Categorias::find($id);      

            $busca = Productos::where('idcategoria', $id);
            
            if(!isset($categoria)){
                header('Location: /admin/mantenimiento/productos/categorias');
            }
            if($busca){
                $alertas['error'][] = 'LA CATEGORIA YA ESTA SIENDO USADA EN PRODUCTOS';
            }else{
                $resultado = $categoria->eliminar();
            
                if($resultado){
                    header('Location: /admin/mantenimiento/productos/categorias'); 
                } 
            }

            // Renderizamos la vista con las alertas
            $router->render('admin/mantenimiento/productos/categorias/index', [
                'titulo' => 'Categorias',
                'alertas' => $alertas,
                'categorias'=>$categorias,
                'opciones'=>$opciones  
            ]);            
      
        }
    }

    public static function cargar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $categoria = new Categorias;
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
                $nombresCampos = ['codigo','nombre'];
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
                    $datosFila['idestado'] = '9'; 
                    $datosFila['idusercrea'] = $_SESSION['id']; // Agregar el ID del usuario al array asociativo  
                    $datosFila['idempresa'] = $_SESSION['idempresa'];                 
                    if (!empty($datosFila)) {
                       
                        $categoria->sincronizar($datosFila);                     
        
                        $alertas = $categoria->validar();
                        
                        if(empty($alertas)){                           
                            $resultado = $categoria->procedureMantenimiento('prm_categorias_masivo',$datosFila);
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

        $router ->render('admin/mantenimiento/productos/categorias/cargar',[
            'titulo' => 'Ingreso Masivo Categorias',
            'alertas'=>$alertas,
            'opciones'=>$opciones
       
        ]);
       
    }

}