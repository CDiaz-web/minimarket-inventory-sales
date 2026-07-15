<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Cliente</legend>
    <button type="button" id="btnTraerCliente" class="formulario__solicitar formulario__solicitar--registrar">Traer Cliente de SUNAT</button>   
    <div class="formulario__campo">
        <label for="tipo_persona" class="formulario__label">Tipo de Persona</label>
        <select name="tipo_persona" id="tipo_persona" class="formulario__select-xl" required>
            <option value="">-- Seleccione --</option>
            <option value="N" <?php echo ($cliente->tipo_persona === 'N') ? 'selected' : ''; ?>>Natural</option>
            <option value="J" <?php echo ($cliente->tipo_persona === 'J') ? 'selected' : ''; ?>>Jurídica</option>
        </select>
    </div>


    <div class="formulario__campo">  
        <label for="documento" class="formulario__label">Ruc/Dni</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "documento"
            name="documento"
            placeholder="Ruc/Dni"   
            value ="<?php echo $cliente->documento;?>"     
        /> 
    </div>  
    <div class="formulario__campo">   
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Nombre"   
            value ="<?php echo $cliente->nombre;?>"     
        />
    </div> 
    <div class="formulario__campo">   
        <label for="apellidos" class="formulario__label">Apellidos</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "apellidos"
            name="apellidos"
            placeholder="Apellidos"   
            value ="<?php echo $cliente->apellidos;?>"     
        />
    </div> 

    <div class="formulario__campo">   
        <label for="razon_social" class="formulario__label">Razon Social</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "razon_social"
            name="razon_social"
            placeholder="Razon Social"   
            value ="<?php echo $cliente->razon_social;?>"     
        />
    </div> 

    <div class="formulario__campo">
        <label for="idtipo" class="formulario__label">Clasificacion</label>
        <select class="formulario__select-xl" id="idtipo" name ="idtipo">
        <option value="" >-Seleccionar-</option>
            <?php foreach($tipos as $tipo) { ?>
                <option <?php echo ($cliente->idtipo === $tipo->id) ? 'selected' : '' ; ?> value="<?php echo $tipo->id; ?>" > <?php echo $tipo->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>

    <div class="formulario__campo">   
        <label for="telefono" class="formulario__label">Telefono</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "telefono"
            name="telefono"
            placeholder="Teléfono"   
            value ="<?php echo $cliente->telefono;?>"     
        />
    </div>   
    
    <div class="formulario__campo">   
        <label for="direccion" class="formulario__label">Direccion</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "direccion"
            name="direccion"
            placeholder="Dirección"   
            value ="<?php echo $cliente->direccion;?>"     
        />
    </div>

    <div class="formulario__campo">
        <label for="idtienda_default" class="formulario__label">Tienda por defecto</label>
        <select class="formulario__select-xl" id="idtienda_default" name ="idtienda_default">
        <option value="" >-Seleccionar-</option>
            <?php foreach($tiendas as $tienda) { ?>
                <option <?php echo ((int)$cliente->idtienda_default === (int)$tienda->id) ? 'selected' : '' ; ?> value="<?php echo $tienda->id; ?>" > <?php echo $tienda->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
    <div class="formulario__campo">
        <label for="idlista" class="formulario__label">Lista de Precios</label>
        <select class="formulario__select-xl" id="idlista" name ="idlista">
        <option value="" >-Seleccionar-</option>
            <?php foreach($listas as $lista) { ?>
                <option <?php echo ($cliente->idlista == $lista->id) ? 'selected' : '' ; ?> value="<?php echo $lista->id; ?>" > <?php echo $lista->descripcion; ?> </option>
            <?php }?> 
        </select>
    </div>
  

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($cliente) && $cliente->id)  
                ? ($cliente->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($cliente) && $cliente->id) 
                    ? ($cliente->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>

</fieldset>