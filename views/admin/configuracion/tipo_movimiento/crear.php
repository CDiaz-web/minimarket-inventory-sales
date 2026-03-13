<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="table-header">
    <a class="boton boton--primary-link" href="/admin/configuracion/tipo_movimiento">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>


<div class="form-wrapper">

   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>

    <form method="POST" action="/admin/configuracion/tipo_movimiento/crear"  enctype="multipart/form-data" class="formulario">
        <?php  include_once __DIR__ . '/formulario.php' ?>

        <input class="formulario__submit formulario__submit--registrar" type="submit" value="Registrar Unidad Medida" id="btnGuardar">
    </form>

</div>