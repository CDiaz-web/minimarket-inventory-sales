<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Lista de Precio</legend> 

    <div class="formulario__campo">    
        <label for="codigo" class="formulario__label">Código</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "codigo"
            name="codigo"
            placeholder="Código Lista"   
            maxlength="3"
            value ="<?php echo $lista->codigo;?>"     
        />
    </div>  
    <div class="formulario__campo">    
        <label for="descripcion" class="formulario__label">Descripcion</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "descripcion"
            name="descripcion"
            placeholder="Descripcion"   
            maxlength="100"
            value ="<?php echo $lista->descripcion;?>"     
        />
    </div>   
    
    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($lista) && $lista->id)  
                ? ($lista->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($lista) && $lista->id) 
                    ? ($lista->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>

</fieldset>