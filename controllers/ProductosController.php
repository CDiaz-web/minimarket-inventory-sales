<?php

namespace Controllers;
require '../vendor/autoload.php';
use Model\Categorias;
use Model\Productos;
use Model\Opciones;
use Model\Unidades;
use MVC\Router;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Intervention\Image\ImageManagerStatic as Image;
use Model\Estados;

class ProductosController {
    public static function index(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }       

        // $productos = Productos::all('ASC');
        $alertas = [];
        $valor = [$_SESSION['idempresa']]; 
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $productos = Productos::procedureLista('prc_productos_lista',$valor);
        $router ->render('admin/mantenimiento/productos/productos/index',[
            'titulo' => 'Productos',
            'alertas' => $alertas,
            'productos'=>$productos,
            'opciones'=>$opciones            
        ]);
    }
    public static function crear(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $valor = $_SESSION['idempresa'];  
        $alertas = [];
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $producto = new Productos;
        $categorias = Categorias::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
        
        $unidades = Unidades::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
    
        $producto->imagen_actual = $producto->imagen; 
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            }
            
            if(!empty($_FILES['imagen']['tmp_name'])){
                $carpeta_imagenes = '../public/img/productos';
                //crear la carpeta de o exixtir               
                if(!is_dir($carpeta_imagenes)){
                    mkdir($carpeta_imagenes,0777,true);
                }

                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png',80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp',80);
                
                $nombre_imagen = md5(uniqid(rand(), true));
                //eliminamos el archivo anterior
                unlink($carpeta_imagenes . '/' . $producto->imagen_actual . ".png");
                unlink($carpeta_imagenes . '/' . $producto->imagen_actual . ".webp");
                //end eliminamos el archivo anterior
                $_POST['imagen'] = $nombre_imagen;

            } else{
                if(!$producto->imagen_actual){
                    $_POST['imagen'] = 'sinimagen';
                }else{
                    $_POST['imagen'] = $producto->imagen_actual;
                }
                
            } 
            $busca_producto = Productos::where('codigo',$_POST['codigo']) ;
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
            $producto->sincronizar($_POST);
            //validar
           
            $alertas = $producto->validar();

            if($busca_producto) {
                Productos::setAlerta('error', 'El Codigo ya fue registrado en otro producto');
                $alertas = Productos::getAlertas();
            }
            //guardar el registro
            if(empty($alertas)){
                //guardar las imagenes
                if(isset($nombre_imagen)){
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");                              
                }
                //guardar en la base de datos
                $resultado = $producto->guardar();
                if($resultado){
                    header('Location: /admin/mantenimiento/productos/productos');
                }
            }
        }
        
      
        $router ->render('admin/mantenimiento/productos/productos/crear',[
            'titulo' => 'Registrar Producto',
            'alertas' => $alertas,
            'producto' => $producto,
            'categorias'=>$categorias,     
            'unidades'=>$unidades,
            'opciones'=>$opciones
        ]);
    }

    public static function editar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $valor = $_SESSION['idempresa'];  
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if(!$id){
            header('Location: /admin/mantenimiento/productos/productos');
        }       
        $producto = Productos::find($id);
        if(!$producto){
            header('Location: /admin/mantenimiento/productos/productos');
        }   
        $categorias = Categorias::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
        $unidades = Unidades::findArray(['idempresa'=> $valor,'activo'=> 1],false) ?? [];
     
        $producto->imagen_actual = $producto->imagen; 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(!is_admin()){
                header('Location: /login');
            } 
            if(!empty($_FILES['imagen']['tmp_name'])){
                $carpeta_imagenes = '../public/img/productos';
                //crear la carpeta de o exixtir               
                if(!is_dir($carpeta_imagenes)){
                    mkdir($carpeta_imagenes,0777,true);
                }

                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png',80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp',80);
                
                $nombre_imagen = md5(uniqid(rand(), true));
                //eliminamos el archivo anterior                
               

                if($producto->imagen_actual!==''){
                    if(file_exists($carpeta_imagenes . '/' . $producto->imagen_actual . ".png")) {
                        unlink($carpeta_imagenes . '/' . $producto->imagen_actual . ".png");
                        unlink($carpeta_imagenes . '/' . $producto->imagen_actual . ".webp");
                    }
                }  
                
                $_POST['imagen'] = $nombre_imagen;
                //end eliminamos el archivo anterior
                

            } else{
                if(!$producto->imagen_actual){
                    $_POST['imagen'] = 'sinimagen';
                }else{
                    $_POST['imagen'] = $producto->imagen_actual;
                }
            } 
        
            //agregamos informacion de auditoria al $_post
            date_default_timezone_set('America/Lima');
            $_POST['idusermodi']=$_SESSION['id'];
            $_POST['fechamodi']=date("Y-m-d H:i:s");
            $_POST['idempresa'] = $_SESSION['idempresa'];
            $activo = isset($_POST['activo']) ? 1 : 0;
            $_POST['activo'] = $activo;            
            $producto->sincronizar($_POST);

            $alertas = $producto->validar();

            if(empty($alertas)){
                //guardar las imagenes
                if(isset($nombre_imagen)){
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");                              
                }
                $resultado = $producto->guardar();            
                if($resultado){             
                    $alertas['exito'][] = 'REGISTRO ACTUALIZADO DE MANERA CORRECTA'; 
                   //header('Location: /admin/logistica/productos');
                }
            }
            
        }

        $router ->render('admin/mantenimiento/productos/productos/editar',[
            'titulo' => 'Actualizar Producto',
            'alertas' => $alertas,
            'producto' => $producto,
            'categorias'=>$categorias,
            'unidades'=>$unidades,
            'opciones'=>$opciones
        ]);
    }
    public static function eliminar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];   
            
            $producto = Productos::find($id);      
            
            if(!isset($producto)){
                header('Location: /admin/mantenimiento/productos/productos');
            }

           $resultado = $producto->eliminar();
           
            if($resultado){
                header('Location: /admin/mantenimiento/productos/productos'); 
            }       
        }
    }

    public static function cargar(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $producto = new Productos;
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
                $nombresCampos = ['codigo','categoria','nombre','costo', 'venta','unidad','stock','stock_minimo','ventas','idtienda','idusercrea'];
     
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
 
                    $datosFila['idtienda'] = $_SESSION['idtienda']; // Agregar el ID del usuario al array asociativo    
                    $datosFila['idusercrea'] = $_SESSION['id'];      
                    $datosFila['idempresa'] = $_SESSION['idempresa'];
                    
                    if (!empty($datosFila)) {
                       
                    //   $categoria->sincronizar($datosFila);                     
        
                    //     $alertas = $categoria->validar();
                        
                        if(empty($alertas)){                           
                            $resultado = $producto->procedureMantenimiento('prm_productos_masivo',$datosFila);
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

        $router ->render('admin/mantenimiento/productos/productos/cargar',[
            'titulo' => 'Ingreso Masivo Producto',
            'alertas'=>$alertas,
            'opciones'=>$opciones
       
        ]);
       
    }

    // public static function activas() {
    //     header('Content-Type: application/json');
    //     $valor = $_SESSION['idempresa'];      
    //     $productos = TiendaProductosas::findArray(['idempresa'=> $valor,'idestado'=> 7],false) ?? [];
    //     echo json_encode($productos);
    // }

  public static function buscar(){        
   
        header('Content-Type: application/json');

        $empresaId = $_SESSION['idempresa'];

        $q = trim($_GET['q'] ?? '');
        $page = (int)($_GET['page'] ?? 1);

        $limit = 10;
        $offset = ($page - 1) * $limit;



        $productos = Productos::buscarPaginado(
            $empresaId,
            $q,
            $limit,
            $offset
        );

        $total = Productos::contarBusqueda(
            $empresaId,
            $q
        );

        $last_page = ceil($total / $limit);

        echo json_encode([
            'data' => array_map(fn($p) => $p->toPublicArray(), $productos),
            'page' => $page,
            'last_page' => $last_page
        ]);

        exit;
    }
    
}