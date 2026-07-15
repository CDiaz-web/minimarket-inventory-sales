<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Tiendas</legend> 
    <div class="formulario__campo">    
        <label for="codigo" class="formulario__label">Código</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "codigo"
            name="codigo"
            placeholder="Codigo de Tienda"   
            maxlength="4"
            value ="<?php echo $tienda->codigo;?>"     
        />
    </div> 
    <div class="formulario__campo">    
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Nombre Tienda"   
            value ="<?php echo $tienda->nombre;?>"     
        />
    </div>  
    <div class="formulario__campo">    
        <label for="direccion" class="formulario__label">Dirección</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "direccion"
            name="direccion"
            placeholder="Direccion"   
            value ="<?php echo $tienda->direccion;?>"     
        />
    </div> 

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($tienda) && $tienda->id)  
                ? ($tienda->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($tienda) && $tienda->id) 
                    ? ($tienda->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>        

    </div>
</fieldset>