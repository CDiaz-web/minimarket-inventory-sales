<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Producto</legend>

    <div class="formulario__campo">  
        <label for="codigo" class="formulario__label">Código</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "codigo"
            name="codigo"
            placeholder="Código de Barra"   
            value ="<?php echo $producto->codigo;?>"     
        /> 
    </div>  
    <div class="formulario__campo">   
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Nombre Producto"   
            value ="<?php echo $producto->nombre;?>"     
        />
    </div> 
    <div class="formulario__campo">
        <label for="idcategoria" class="formulario__label">Categoría</label>
        <select class="formulario__select-xl" id="idcategoria" name ="idcategoria">
        <option value="" >-Seleccionar-</option>
            <?php foreach($categorias as $categoria) { ?>
                <option <?php echo ($producto->idcategoria === $categoria->id) ? 'selected' : '' ; ?> value="<?php echo $categoria->id; ?>" > <?php echo $categoria->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
    <div class="formulario__campo">
        <label for="idunidad_medida" class="formulario__label">Unidad de Medida</label>
        <select class="formulario__select-xl" id="idunidad_medida" name ="idunidad_medida">
        <option value="" >-Seleccionar-</option>
            <?php foreach($unidades as $unidad) { ?>
                <option <?php echo ((int)$producto->idunidad_medida === (int)$unidad->id) ? 'selected' : '' ; ?> value="<?php echo $unidad->id; ?>" > <?php echo $unidad->nombre; ?> </option>
            <?php }?> 
        </select>

    </div>
    <div class="formulario__campo">   
        <label for="costo" class="formulario__label">Costo</label>
        <input
            type = "number"
            class = "formulario__input"
            id = "costo"
            name="costo"
            placeholder="0.00"   
            step="0.01"
            min="0"            
            value ="<?php echo number_format($producto->costo, 2, '.', ''); ?>"   
        />
    </div>
    <div class="formulario__campo">   
        <label for="venta" class="formulario__label">Precio Venta Sugerida</label>
        <input
            type = "number"
            class = "formulario__input"
            id = "venta"
            name="venta"
            placeholder="0.00"   
            step="0.01"
            min="0"        
            value ="<?php echo number_format($producto->venta, 2, '.', ''); ?>"        
        />
    </div>  
    <div class="formulario__campo">
        <label for="idestado" class="formulario__label">Estado</label>
        <select class="formulario__select-xl" id="idestado" name ="idestado">
        <option value="" >-Seleccionar-</option>
            <?php foreach($estados as $estado) { ?>
                <option <?php echo ($producto->idestado == $estado->id) ? 'selected' : '' ; ?> value="<?php echo $estado->id; ?>" > <?php echo $estado->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
    <div class="formulario__campo">
        <label for="imagen" class="formulario__label">Imagen</label>
        <input
            type = "file"
            class = "formulario__input formulario__input--file"
            id = "imagen"
            name="imagen"
        />
    </div>    
    
    <?php if(($producto->imagen)!=='') { ?>    
        <!-- <p class="formulario__texto">Logo Actual:</p> -->
        <div class="formulario__imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/img/productos/' . $producto->imagen; ?>.webp" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/img/productos/' . $producto->imagen; ?>.png" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/img/productos/' . $producto->imagen; ?>.png" alt="Imagen Producto">
            </picture>
        </div>
    <?php } ?>


</fieldset>