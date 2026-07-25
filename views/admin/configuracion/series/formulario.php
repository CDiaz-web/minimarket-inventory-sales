<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Tipo Pago</legend> 

    <div class="formulario__campo">    
        <label for="codigo" class="formulario__label">Código</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "codigo"
            name="codigo"
            placeholder="Código"   
            maxlength="3"
            value ="<?php echo $tipopago->codigo;?>"     
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
            value ="<?php echo $tipopago->nombre;?>"     
        />
    </div>   


    <div class="formulario__campo formulario__campo--check"> 
        <label for="requiere_cobro" class="formulario__check">
            <input
                type = "checkbox"
                class = "formulario__input"
                id = "requiere_cobro"
                name="requiere_cobro"   
                <?php if ($tipopago->requiere_cobro == 1) echo 'checked="checked"'; ?> 
            /> Requiere Cobro
        </label>
    </div>     

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($tipopago) && $tipopago->id)  
                ? ($tipopago->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($tipopago) && $tipopago->id) 
                    ? ($tipopago->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>
    
</fieldset>