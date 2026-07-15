<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Categoria</legend> 

    <div class="formulario__campo">    
        <label for="codigo" class="formulario__label">Código</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "codigo"
            name="codigo"
            placeholder="Código Categoria"
            maxlength="4"   
            value ="<?php echo $categoria->codigo;?>"     
        />
    </div>  

    <div class="formulario__campo">    
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Nombre Categoria"   
            value ="<?php echo $categoria->nombre;?>"     
        />
    </div>  

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($categoria) && $categoria->id)  
                ? ($categoria->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($categoria) && $categoria->id) 
                    ? ($categoria->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>

    
</fieldset>