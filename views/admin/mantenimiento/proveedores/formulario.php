<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Proveedores</legend>
    <button type="button" id="btnTraerCliente" class="formulario__solicitar formulario__solicitar--registrar">Traer Proveedor de SUNAT</button>   
    <div class="formulario__campo">
        <label for="tipo_persona" class="formulario__label">Tipo de Persona</label>
        <select name="tipo_persona" id="tipo_persona" class="formulario__select-xl" required>
            <option value="">-- Seleccione --</option>
            <option value="N" <?php echo ($proveedor->tipo_persona === 'N') ? 'selected' : ''; ?>>Natural</option>
            <option value="J" <?php echo ($proveedor->tipo_persona === 'J') ? 'selected' : ''; ?>>Jurídica</option>
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
            value ="<?php echo $proveedor->documento;?>"     
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
            value ="<?php echo $proveedor->nombre;?>"     
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
            value ="<?php echo $proveedor->apellidos;?>"     
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
            value ="<?php echo $proveedor->razon_social;?>"     
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
            value ="<?php echo $proveedor->direccion;?>"     
        />
    </div>

    <div class="formulario__campo">   
        <label for="telefono" class="formulario__label">Telefono</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "telefono"
            name="telefono"
            placeholder="Teléfono"   
            value ="<?php echo $proveedor->telefono;?>"     
        />
    </div>   
    


    <div class="formulario__campo">    
        <label for="email" class="formulario__label">E-mail</label>
        <input
            type = "email"
            class = "formulario__input"
            id = "email"
            name="email"
            placeholder="email@email.com"   
            value ="<?php echo $proveedor->email;?>"     
        />
    </div> 
  
    <div class="formulario__campo">   
        <label for="contacto" class="formulario__label">Contacto</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "contacto"
            name="contacto"
            placeholder="Contacto"   
            value ="<?php echo $proveedor->direccion;?>"     
        />
    </div>    

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($proveedor) && $proveedor->id)  
                ? ($proveedor->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($proveedor) && $proveedor->id) 
                    ? ($proveedor->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>

</fieldset>