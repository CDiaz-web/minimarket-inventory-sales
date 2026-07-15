<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Unidad de Medida</legend> 
    <div class="formulario__campo">    
        <label for="codigo" class="formulario__label">Código</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "codigo"
            name="codigo"
            placeholder="Código"   
            maxlength="3"
            value ="<?php echo $unidad->codigo;?>"     
        />
    </div>  
    <div class="formulario__campo">    
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Descripcion"   
            maxlength="45"
            value ="<?php echo $unidad->nombre;?>"     
        />
    </div>   

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($unidad) && $unidad->id)  
                ? ($unidad->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($unidad) && $unidad->id) 
                    ? ($unidad->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>

</fieldset>