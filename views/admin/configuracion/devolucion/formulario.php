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
    <div class="formulario__campo">
        <label for="idestado" class="formulario__label">Estado</label>
        <select class="formulario__select-xl" id="idestado" name ="idestado">
        <option value="" >-Seleccionar-</option>
            <?php foreach($estados as $estado) { ?>
                <option <?php echo ($devolucion->idestado == $estado->id) ? 'selected' : '' ; ?> value="<?php echo $estado->id; ?>" > <?php echo $estado->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
</fieldset>