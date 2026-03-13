<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arteus - <?php echo $titulo; ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- App CSS -->
    <link rel="stylesheet" href="/build/css/app.css">
</head>

<body>

<?php 
    if(!isset($_SESSION['id'])){
        include_once __DIR__ .'/templates/header.php';
    }

    echo $contenido;

    if(!isset($_SESSION['id'])){
        include_once __DIR__ .'/templates/footer.php'; 
    }
?>

<!-- Vendor JS -->
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" defer></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- App JS (NUEVO CORE) -->
<script src="/build/js/app.min.js" defer></script>

</body>
</html>