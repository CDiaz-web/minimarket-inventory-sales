<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="table-header">
    <a class="boton boton--primary-link" href="/admin/mantenimiento/productos/productos">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>

<div class="form-wrapper">
    
    <?php require_once __DIR__ . '../../../../../templates/alertas.php'; ?>

    <form  
        action="/admin/mantenimineto/productos/productos/cargar" 
        method="POST" 
        enctype="multipart/form-data" 
        id="form_carga"
        class="formulario formulario--upload"
        data-upload
    >

        <p class="formulario__titulo-cargar">
            Seleccionar Archivo de Carga (Excel)
        </p>

        <div class="formulario__campo formulario__campo--file">
            <input 
                type="file" 
                name="fileProductos" 
                id="fileProductos" 
                accept=".xls, .xlsx"
                hidden
            >

            <label for="fileProductos" class="boton boton--outline">
                Seleccionar archivo
            </label>

            <span id="fileName" class="formulario__file-name">
                Ningún archivo seleccionado
            </span>
        </div>

        <button type="submit" class="boton boton--primary boton--full" id="btnCargar">
            Cargar Categorías
        </button>

        <div class="formulario__loading" hidden>
            <img src="/img/loading.gif" alt="Cargando...">
        </div>

    </form>

</div>