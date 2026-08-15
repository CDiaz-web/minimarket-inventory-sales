<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Tipo Movimiento</legend> 
    <div class="formulario__campo">    
        <label for="codigo" class="formulario__label">Código</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "codigo"
            name="codigo"
            placeholder="Codigo"   
            maxlength="20"
            value ="<?php echo $tipo->codigo;?>"     
        />
    </div> 
    <div class="formulario__campo">    
        <label for="nombre" class="formulario__label">Descripción</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Descripcion del Movimiento"   
            value ="<?php echo $tipo->nombre;?>"     
        />
    </div>  
 
    <div class="formulario__campo">    
        <label for="accion" class="formulario__label">Accion</label>
        <select class="formulario__select-xl" id="accion" name ="accion">
            <option value="" >-Seleccionar-</option>
            <option <?php echo ($tipo->accion === "Suma") ? 'selected' : '' ; ?> value="Suma">Suma</option>
            <option <?php echo ($tipo->accion === "Resta") ? 'selected' : '' ; ?> value="Resta">Resta</option>
        </select>
    </div>  
    <div class="formulario__campo">    
        <label for="tipo_documento" class="formulario__label">Tipo Documento</label>
        <select class="formulario__select-xl" id="tipo_documento" name ="tipo_documento">
            <option value="" >-Seleccionar-</option>
            <option <?php echo ($tipo->tipo_documento === "Inventario") ? 'selected' : '' ; ?> value="Inventario">Inventario</option>
            <option <?php echo ($tipo->tipo_documento === "Compra") ? 'selected' : '' ; ?> value="Venta">Compra</option>
            <option <?php echo ($tipo->tipo_documento === "Venta") ? 'selected' : '' ; ?> value="Venta">Venta</option>
        </select>
    </div>  

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($tipo) && $tipo->id)  
                ? ($tipo->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($tipo) && $tipo->id) 
                    ? ($tipo->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>        

    </div>
</fieldset>