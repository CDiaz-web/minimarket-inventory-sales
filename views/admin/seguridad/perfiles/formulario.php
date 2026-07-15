<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Perfil</legend> 

    <div class="formulario__campo">    
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Nombre Perfil"   
            value ="<?php echo $perfil->nombre;?>"     
        />
    </div>  
    <div class="formulario__campo">
        <label for="inicial" class="formulario__label">Página Inicial</label>
        <select class="formulario__select" id="inicial" name ="inicial">
        <option value="" >-Seleccionar-</option>
            <?php foreach($opciones as $opcion) { ?>
                <option <?php echo ($perfil->inicial == $opcion->id) ? 'selected' : '' ; ?> value="<?php echo $opcion->id; ?>" > <?php echo $opcion->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($perfil) && $perfil->id)  
                ? ($perfil->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($perfil) && $perfil->id) 
                    ? ($perfil->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>

</fieldset>