<fieldset class="formulario__fieldset">
    
    <legend class="formulario__legend">Informacion Motivo Devolucion</legend> 

    <div class="formulario__campo">    
        <label for="nombre" class="formulario__label">Descripción</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Descripcion del Movimiento"   
            value ="<?php echo $devolucion->nombre;?>"     
        />
    </div> 

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($devolucion) && $devolucion->id)  
                ? ($devolucion->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($devolucion) && $devolucion->id) 
                    ? ($devolucion->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>
</fieldset>