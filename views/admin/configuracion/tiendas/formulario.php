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
    <div class="formulario__campo">
        <label for="idestado" class="formulario__label">Estado</label>
        <select class="formulario__select" id="idestado" name ="idestado">
        <option value="" >-Seleccionar-</option>
            <?php foreach($estados as $estado) { ?>
                <option <?php echo ($tienda->idestado == $estado->id) ? 'selected' : '' ; ?> value="<?php echo $estado->id; ?>" > <?php echo $estado->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
</fieldset>