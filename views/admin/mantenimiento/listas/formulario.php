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
    <div class="formulario__campo">
        <label for="idmoneda" class="formulario__label">Moneda</label>
        <select class="formulario__select-xl" id="idmoneda" name ="idmoneda">
        <option value="" >-Seleccionar-</option>
            <?php foreach($monedas as $moneda) { ?>
                <option <?php echo ($lista->idmoneda == $moneda->id) ? 'selected' : '' ; ?> value="<?php echo $moneda->id; ?>" > <?php echo $moneda->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
    <div class="formulario__campo">
        <label for="idestado" class="formulario__label">Estado</label>
        <select class="formulario__select-xl" id="idestado" name ="idestado">
        <option value="" >-Seleccionar-</option>
            <?php foreach($estados as $estado) { ?>
                <option <?php echo ($lista->idestado == $estado->id) ? 'selected' : '' ; ?> value="<?php echo $estado->id; ?>" > <?php echo $estado->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
</fieldset>