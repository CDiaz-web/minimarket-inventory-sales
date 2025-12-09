<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Clasificacion de Cliente</legend> 

    <div class="formulario__campo">    
        <label for="codigo" class="formulario__label">Código</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "codigo"
            name="codigo"
            placeholder="Código Tipo Pago"   
            maxlength="3"
            value ="<?php echo $tipo->codigo;?>"     
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
            value ="<?php echo $tipo->nombre;?>"     
        />
    </div>   
    <div class="formulario__campo">
        <label for="idlista" class="formulario__label">Lista de Precios</label>
        <select class="formulario__select-xl" id="idlista" name ="idlista">
        <option value="" >-Seleccionar-</option>
            <?php foreach($listas as $lista) { ?>
                <option <?php echo ($tipo->idlista == $lista->id) ? 'selected' : '' ; ?> value="<?php echo $lista->id; ?>" > <?php echo $lista->descripcion; ?> </option>
            <?php }?> 
        </select>
    </div>
        <div class="formulario__campo">
        <label for="idestado" class="formulario__label">Estado</label>
        <select class="formulario__select-xl" id="idestado" name ="idestado">
        <option value="" >-Seleccionar-</option>
            <?php foreach($estados as $estado) { ?>
                <option <?php echo ($tipo->idestado == $estado->id) ? 'selected' : '' ; ?> value="<?php echo $estado->id; ?>" > <?php echo $estado->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
</fieldset>