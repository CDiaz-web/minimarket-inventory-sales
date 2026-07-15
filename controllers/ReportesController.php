<?php

namespace Controllers;
use Services\PdfService;
use Model\Reportes;
use Model\Opciones;
use Model\Empresa;
use MVC\Router;

require '../vendor/autoload.php';
// use PhpOffice\PhpSpreadsheet\IOFactory;


class ReportesController {

    public static function index(Router $router){
        if(!is_admin()){
            header('Location: /login');
        }   
        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 

        $valor = [$_SESSION['idperfil']]; 
        $reportes = Opciones::procedureLista('prc_lista_reportes_permitidos',$valor);    

        $router ->render('admin/gestion/reportes/index',[
            'titulo_uno' => 'Reportes del Sistema',          
            'reportes'=>$reportes,
            'opciones'=>$opciones           
        ]);
    }
    
   
    
    public static function fecha(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];

        $idTienda = $_SESSION['idtienda'];

        // Fechas por defecto
        $fechaInicio = $_GET['fecha_inicial'] ?? date('Y-m-01');
        $fechaFin    = $_GET['fecha_final'] ?? date('Y-m-d');

        // Filtros para la vista
        $filtros = [
            'fecha_inicial' => $fechaInicio,
            'fecha_final'   => $fechaFin
        ];

        // detalle
        $ventas = Reportes::ventasPorTienda(
            $idTienda,
            $fechaInicio,
            $fechaFin,
            1
        );

        // resumen
        $resumen = Reportes::ventasPorTienda(
            $idTienda,
            $fechaInicio,
            $fechaFin,
            2
        );

        $router->render(
            'admin/gestion/reportes/fecha/index',
            [
                'titulo' => 'Ventas por Rango de Fechas',
                'ventas' => $ventas,
                'resumen' => $resumen[0] ?? null,
                'filtros' => $filtros,
                'alertas' => $alertas,
                'opciones' => $opciones
            ]
        );
    }    
     public static function cliente(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];

        $idTienda = $_SESSION['idtienda'];

        // Fechas por defecto
        $fechaInicio = $_GET['fecha_inicial'] ?? date('Y-m-01');
        $fechaFin    = $_GET['fecha_final'] ?? date('Y-m-d');

        // Filtros para la vista
        $filtros = [
            'fecha_inicial' => $fechaInicio,
            'fecha_final'   => $fechaFin
        ];

        // detalle
        $venta_clientes = Reportes::ventasPorCliente(
            $idTienda,
            $fechaInicio,
            $fechaFin            
        );        

        $router->render(
            'admin/gestion/reportes/cliente/index',
            [
                'titulo' => 'Ventas por Cliente',
                'venta_clientes' => $venta_clientes,             
                'filtros' => $filtros,
                'alertas'=>$alertas,
                'opciones'=>$opciones
            ]
        );
    }   
    
    

    public static function productos(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];

        $idTienda = $_SESSION['idtienda'];

        // Fechas por defecto
        $fechaInicio = $_GET['fecha_inicial'] ?? date('Y-m-01');
        $fechaFin    = $_GET['fecha_final'] ?? date('Y-m-d');

        // Filtros para la vista
        $filtros = [
            'fecha_inicial' => $fechaInicio,
            'fecha_final'   => $fechaFin
        ];

        // detalle
        $ventas = Reportes::ventasPorProducto(
            $idTienda,
            $fechaInicio,
            $fechaFin            
        );

        //debuguear($ventas);

        $router->render(
            'admin/gestion/reportes/productos/index',
            [
                'titulo' => 'Ventas por Productos',
                'ventas' => $ventas,             
                'filtros' => $filtros,
                'alertas'=>$alertas,
                'opciones'=>$opciones
            ]
        );
    }   

    public static function estado(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];

        $idTienda = $_SESSION['idtienda'];

        // Fechas por defecto
        $fechaInicio = $_GET['fecha_inicial'] ?? date('Y-m-01');
        $fechaFin    = $_GET['fecha_final'] ?? date('Y-m-d');

        // Filtros para la vista
        $filtros = [
            'fecha_inicial' => $fechaInicio,
            'fecha_final'   => $fechaFin
        ];

        // detalle
        $ventas = Reportes::ventasPorEstado(
            $idTienda,
            $fechaInicio,
            $fechaFin            
        );

        //debuguear($ventas);

        $router->render(
            'admin/gestion/reportes/estado/index',
            [
                'titulo' => 'Ventas por Estado',
                'ventas' => $ventas,             
                'filtros' => $filtros,
                'alertas'=>$alertas,
                'opciones'=>$opciones
            ]
        );
    }   


    public static function inventario(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $opciones = Opciones::opcionesMenu($_SESSION['idperfil']); 
        $alertas = [];

        $idEmpresa = $_SESSION['idempresa'];
        $idTienda = $_SESSION['idtienda'];

        // Fechas por defecto
        $fechaInicio = $_GET['fecha_inicial'] ?? date('Y-m-01');
        $fechaFin    = $_GET['fecha_final'] ?? date('Y-m-d');

        // Filtros para la vista
        $filtros = [
            'fecha_inicial' => $fechaInicio,
            'fecha_final'   => $fechaFin
        ];


        // detalle
        $ventas = Reportes::Inventario(
            $idEmpresa,
            $idTienda            
        );

        //debuguear($ventas);

        $router->render(
            'admin/gestion/reportes/inventario/index',
            [
                'titulo' => 'Inventario',
                'ventas' => $ventas,             
                'filtros' => $filtros,
                'alertas'=>$alertas,
                'opciones'=>$opciones
            ]
        );
    }   

    public static function pdffecha(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $datos_empresa = Empresa::find($_SESSION['idempresa']);

        $idTienda = $_SESSION['idtienda'];



        $fechaInicio = !empty($_POST['fecha_inicial'])
            ? $_POST['fecha_inicial']
            : date('Y-m-01');

        $fechaFin = !empty($_POST['fecha_final'])
            ? $_POST['fecha_final']
            : date('Y-m-d');
        $empresa =  $datos_empresa->nombre;
        $tienda =  $_SESSION['tienda'];
        $fecha_impresion = date('d/m/Y H:i');
        $rango_fechas = "Desde {$fechaInicio} hasta {$fechaFin}"; 

        $logo = null;

        $logoPath = $_SERVER['DOCUMENT_ROOT']
            . '/img/'
            . $datos_empresa->logo . '.png';

        if (file_exists($logoPath)) {

            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);

            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $ventas = Reportes::ventasPorTienda(
            $idTienda,
            $fechaInicio,
            $fechaFin,
            1
        );


        $resumen = Reportes::ventasPorTienda(
            $idTienda,
            $fechaInicio,
            $fechaFin,
            2
        );

        $resumen = $resumen[0] ?? null;


       // 🔹 datos para la plantilla PDF
            $titulo = "Reporte de Ventas";
        // 🔹 generar HTML usando una vista

        $columnas = [
            "Numero",
            "Fecha",
            "Cliente",
            "Sub Total",
            "Igv",
            "Total",
            "Aprobado Por"
        ];
        $datos = [];

        foreach ($ventas as $venta) {
            $datos[] = [
                $venta->numero,
                $venta->fechaapro,
                $venta->cliente,
                number_format($venta->subtotal, 2),
                number_format($venta->igv, 2),
                number_format($venta->total, 2),
                $venta->aprobado_por
            ];
        }

        ob_start();

        include ROOT_PATH . '/views/admin/gestion/reportes/pdf/plantilla.php';


        $html = ob_get_clean();


        if (ob_get_length()) {
            ob_end_clean();
        }
    
        // 🔹 generar PDF
        PdfService::generar($html, 'reporte_ventas.pdf', 'landscape');
    } 

    public static function pdfcliente(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $datos_empresa = Empresa::find($_SESSION['idempresa']);

        $idTienda = $_SESSION['idtienda'];



        $fechaInicio = !empty($_POST['fecha_inicial'])
            ? $_POST['fecha_inicial']
            : date('Y-m-01');

        $fechaFin = !empty($_POST['fecha_final'])
            ? $_POST['fecha_final']
            : date('Y-m-d');
        $empresa =  $datos_empresa->nombre;
        $tienda =  $_SESSION['tienda'];
        $fecha_impresion = date('d/m/Y H:i');
        $rango_fechas = "Desde {$fechaInicio} hasta {$fechaFin}"; 

        $logo = null;

        $logoPath = $_SERVER['DOCUMENT_ROOT']
            . '/img/'
            . $datos_empresa->logo . '.png';

        if (file_exists($logoPath)) {

            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);

            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // detalle
        $ventas = Reportes::ventasPorCliente(
            $idTienda,
            $fechaInicio,
            $fechaFin            
        );
       // 🔹 datos para la plantilla PDF
            $titulo = "Reporte de Ventas por Cliente";
        // 🔹 generar HTML usando una vista

        $columnas = [
            "Documento",
            "Cliente",
            "Cantidad",
            "Sub Total",
            "Promedio",           
            "Ultima Compra"
        ];
        $datos = [];

        foreach ($ventas as $venta) {
            $datos[] = [
                $venta->documento,
                $venta->cliente,       
                number_format($venta->cantidad_ordenes, 2),
                number_format($venta->subtotal_vendido, 2),
                number_format($venta->ticket_promedio, 2),
                $venta->fechaapro
            ];
        }

        ob_start();

        include ROOT_PATH . '/views/admin/gestion/reportes/pdf/plantilla.php';


        $html = ob_get_clean();


        if (ob_get_length()) {
            ob_end_clean();
        }
    
        // 🔹 generar PDF
        PdfService::generar($html, 'reporte_ventas_clientes.pdf', 'landscape');
    } 
    public static function pdfproductos(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $datos_empresa = Empresa::find($_SESSION['idempresa']);

        $idTienda = $_SESSION['idtienda'];



        $fechaInicio = !empty($_POST['fecha_inicial'])
            ? $_POST['fecha_inicial']
            : date('Y-m-01');

        $fechaFin = !empty($_POST['fecha_final'])
            ? $_POST['fecha_final']
            : date('Y-m-d');
        $empresa =  $datos_empresa->nombre;
        $tienda =  $_SESSION['tienda'];
        $fecha_impresion = date('d/m/Y H:i');
        $rango_fechas = "Desde {$fechaInicio} hasta {$fechaFin}"; 

        $logo = null;

        $logoPath = $_SERVER['DOCUMENT_ROOT']
            . '/img/'
            . $datos_empresa->logo . '.png';

        if (file_exists($logoPath)) {

            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);

            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // detalle
        $ventas = Reportes::ventasPorProducto(
            $idTienda,
            $fechaInicio,
            $fechaFin            
        );
       // 🔹 datos para la plantilla PDF
            $titulo = "Reporte de Ventas por Producto";
        // 🔹 generar HTML usando una vista

        $columnas = [
            "Codigo",
            "Producto",
            "Cantidad",
            "Sub Total",
            "Igv",           
            "Total",
            "Precio Promedio"
        ];
        $datos = [];

        foreach ($ventas as $venta) {
            $datos[] = [
                $venta->codigo,
                $venta->producto,       
                number_format($venta->cantidad_vendida, 2),
                number_format($venta->subtotal_vendido, 2),
                number_format($venta->total_igv, 2),
                number_format($venta->total_general, 2),
                number_format($venta->precio_promedio, 2)                
            ];
        }

        ob_start();

        include ROOT_PATH . '/views/admin/gestion/reportes/pdf/plantilla.php';


        $html = ob_get_clean();


        if (ob_get_length()) {
            ob_end_clean();
        }
    
        // 🔹 generar PDF
        PdfService::generar($html, 'reporte_ventas_producto.pdf', 'portrait');
    } 

    public static function pdfestado(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $datos_empresa = Empresa::find($_SESSION['idempresa']);

        $idTienda = $_SESSION['idtienda'];



        $fechaInicio = !empty($_POST['fecha_inicial'])
            ? $_POST['fecha_inicial']
            : date('Y-m-01');

        $fechaFin = !empty($_POST['fecha_final'])
            ? $_POST['fecha_final']
            : date('Y-m-d');
        $empresa =  $datos_empresa->nombre;
        $tienda =  $_SESSION['tienda'];
        $fecha_impresion = date('d/m/Y H:i');
        $rango_fechas = "Desde {$fechaInicio} hasta {$fechaFin}"; 

        $logo = null;

        $logoPath = $_SERVER['DOCUMENT_ROOT']
            . '/img/'
            . $datos_empresa->logo . '.png';

        if (file_exists($logoPath)) {

            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);

            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // detalle
        $ventas = Reportes::ventasPorEstado(
            $idTienda,
            $fechaInicio,
            $fechaFin            
        );
       // 🔹 datos para la plantilla PDF
            $titulo = "Reporte de Ventas por Estado";
        // 🔹 generar HTML usando una vista

        $columnas = [
            "Estado",
            "Cantidad",
            "Sub Total",
            "Igv",           
            "Total"
        ];
        $datos = [];

        foreach ($ventas as $venta) {
            $datos[] = [
                $venta->estado,       
                number_format($venta->cantidad_ordenes, 2),
                number_format($venta->subtotal, 2),
                number_format($venta->total_igv, 2),
                number_format($venta->total_general, 2)               
            ];
        }

        ob_start();

        include ROOT_PATH . '/views/admin/gestion/reportes/pdf/plantilla.php';


        $html = ob_get_clean();


        if (ob_get_length()) {
            ob_end_clean();
        }
    
        // 🔹 generar PDF
        PdfService::generar($html, 'reporte_ventas_producto.pdf', 'portrait');
    } 

    public static function pdfinventario(Router $router)
    {
        if (!is_auth()) {
            header('Location: /login');
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $datos_empresa = Empresa::find($_SESSION['idempresa']);

        $idTienda = $_SESSION['idtienda'];



        $fechaInicio = !empty($_POST['fecha_inicial'])
            ? $_POST['fecha_inicial']
            : date('Y-m-01');

        $fechaFin = !empty($_POST['fecha_final'])
            ? $_POST['fecha_final']
            : date('Y-m-d');
        $empresa =  $datos_empresa->nombre;
        $tienda =  $_SESSION['tienda'];
        $fecha_impresion = date('d/m/Y H:i');
        $rango_fechas = "Desde {$fechaInicio} hasta {$fechaFin}"; 

        $logo = null;

        $logoPath = $_SERVER['DOCUMENT_ROOT']
            . '/img/'
            . $datos_empresa->logo . '.png';

        if (file_exists($logoPath)) {

            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);

            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // detalle
        $ventas = Reportes::Inventario(
            $idTienda            
        );
       // 🔹 datos para la plantilla PDF
            $titulo = "Reporte Inventario por Tienda";
        // 🔹 generar HTML usando una vista

        $columnas = [
            "Codigo",
            "Producto",
            "Stock Actual",
            "Stock Reservado",
            "Stock Disponible",
            "Stock Min.",  
            "Stock Max.",            
            "Estado Stock"
        ];
        $datos = [];

        foreach ($ventas as $venta) {
            $datos[] = [
                $venta->codigo,
                $venta->producto,       
                number_format($venta->stock_actual, 2),
                number_format($venta->stock_comprometido, 2),
                number_format($venta->stock_disponible, 2),
                number_format($venta->stock_min, 2),
                number_format($venta->stock_max, 2),
                $venta->estado
            ];
        }

        ob_start();

        include ROOT_PATH . '/views/admin/gestion/reportes/pdf/plantilla.php';


        $html = ob_get_clean();


        if (ob_get_length()) {
            ob_end_clean();
        }
    
        // 🔹 generar PDF
        PdfService::generar($html, 'reporte_inventario_tienda.pdf', 'landscape');
    } 
    
}
