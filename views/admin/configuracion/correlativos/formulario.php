<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Correlativo</legend> 

    <div class="formulario__campo">
        <label for="idtienda" class="formulario__label">Tienda por defecto</label>
        <select class="formulario__select-xl" id="idtienda" name ="idtienda">
        <option value="" >-Seleccionar-</option>
            <?php foreach($tiendas as $tienda) { ?>
                <option <?php echo ((int)$correlativo->idtienda === (int)$tienda->id) ? 'selected' : '' ; ?> value="<?php echo $tienda->id; ?>" > <?php echo $tienda->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
 
    <div class="formulario__campo">    
        <label for="tipo_documento" class="formulario__label">Tipo Documento</label>
        <select class="formulario__select-xl" id="tipo_documento" name ="tipo_documento">
            <option value="" >-Seleccionar-</option>
            <option <?php echo ($correlativo->tipo_documento === "Inventario") ? 'selected' : '' ; ?> value="Inventario">Inventario</option>
            <option <?php echo ($correlativo->tipo_documento === "Venta") ? 'selected' : '' ; ?> value="Venta">Venta</option>
            <option <?php echo ($correlativo->tipo_documento === "Compra") ? 'selected' : '' ; ?> value="Compra">Compra</option>
        </select>
    </div>  
    

</fieldset>