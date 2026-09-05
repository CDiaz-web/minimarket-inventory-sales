<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>        
        Arteus - <?php echo $titulo; ?>
    </title>    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="/build/css/app.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/build/css/app.css">
    <!-- JS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/build/css/app.css">
    <!-- JS Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"  rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- <script src="assets/js/global.js"></script> -->


</head>
    <body>
        <div class="admin">
            <?php
                include_once __DIR__ .'/templates/admin-sidebar.php';  
            ?>
                <!-- overlay mobile -->
            <div class="sidebar-overlay"></div>
            <div class="content">
                <?php 
                    include_once __DIR__ .'/templates/admin-header.php';
                ?>
                <main class="content__body">
                    <?php 
                        echo $contenido; 
                    ?> 
                </main>
            </div>

        </div>
            
        <!-- Librería XLSX desde CDN -->

        <script>
            window.APP = {
                config: {
                    empresa_id: <?php echo $_SESSION['idempresa'] ?? 0; ?>,
                    moneda_base: <?php echo $_SESSION['moneda'] ?? 0; ?>,
                    validar_tc: <?php echo $_SESSION['validar_tc'] ?? 0 ; ?>,
                    variacion_tc: <?php echo $variaciontc ?? 0; ?>,
                    serie: <?php echo $serie_defecto ?? 0; ?>,
                    impuesto: <?php echo $impuesto ?? 0; ?>
                }
            };
        </script>

        <script>
            window.APP = window.APP || {};
            window.APP.compraEdicion = {
                cabecera: <?= json_encode($cabecera, JSON_UNESCAPED_UNICODE) ?>,
                detalle: <?= json_encode($detalle, JSON_UNESCAPED_UNICODE) ?>
            };
            window.APP.ventaEdicion = {
                cabecera: <?= json_encode($cabecera, JSON_UNESCAPED_UNICODE) ?>,
                detalle: <?= json_encode($detalle, JSON_UNESCAPED_UNICODE) ?>
            };
            window.APP.recepcion = {
                idSerieDefecto: <?= (int) $serie_defecto ?>
            };
        </script>

        <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            window.BASE_URL = "/admin";
        </script>
        
        <script src="/build/js/app.min.js" defer></script>

<div id="appModal" class="dashboard-modal hidden">

    <div class="modal__contenido">

        <div class="modal__header">
            <h3 class="modal__title"></h3>
            <button class="modal__close">✕</button>
        </div>

        <div class="modal__body"></div>

        <div class="modal__footer"></div>

    </div>

</div>

    </body>
</html>