<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
<div class="dashboard__contendor-boton">
    <a class="dashboard__boton" href="/admin/mantenimiento/productos/productos">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>

<div class="dashboard__formulario"> 
    <?php        
        require_once __DIR__ . '../../../../../templates/alertas.php';
    ?> 
    <p class="bloques__grid-titulo">Seleccionar Archivo de Carga (Excel):</p>
    <div>
        <form  action="/admin/mantenimineto/productos/productos/cargar" method="POST" enctype="multipart/form-data" id="form_carga"  class="formulario">
            <fieldset class="formulario__fieldset">
                <div class="formulario__campo">
                    <input type="file" name="fileProductos" id="fileProductos" class = "formulario__input" accept=".xls, .xlsx">
                </div>
            </fieldset>  
            <input type="submit" value="Cargar Productos" class="dashboard__botoncargar" id="btnCargar">
        </form>            
    </div>  
</div>

<br>

<div class="formulario__gif">
    <picture>
        <img src="http://localhost:3000/img/loading.gif" id="img_carga">
    </picture>
</div>     