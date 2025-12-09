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
                <option <?php echo ($perfil->inicial === $opcion->id) ? 'selected' : '' ; ?> value="<?php echo $opcion->id; ?>" > <?php echo $opcion->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
    <div class="formulario__campo">
        <label for="idestado" class="formulario__label">Estado</label>
        <select class="formulario__select-xl" id="idestado" name ="idestado">
        <option value="" >-Seleccionar-</option>
            <?php foreach($estados as $estado) { ?>
                <option <?php echo ($perfil->idestado == $estado->id) ? 'selected' : '' ; ?> value="<?php echo $estado->id; ?>" > <?php echo $estado->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
</fieldset>