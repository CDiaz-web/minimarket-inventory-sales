<fieldset class="formulario__fieldset-xl">
    <legend class="formulario__legend">Informacion Usuarios</legend> 

    <div class="formulario__campo">    
        <label for="nombre" class="formulario__label">Nombre</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Nombre"   
            value ="<?php echo $usuario->nombre;?>"     
        />
    </div>  
    <div class="formulario__campo">    
        <label for="apellido" class="formulario__label">Apellido</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "apellido"
            name="apellido"
            placeholder="Apellidos"   
            value ="<?php echo $usuario->apellido;?>"     
        />
    </div> 
    <div class="formulario__campo">
        <label for="idperfil" class="formulario__label">Perfil</label>
        <select class="formulario__select-xl" id="idperfil" name ="idperfil">
        <option value="" >-Seleccionar-</option>
            <?php foreach($perfiles as $perfil) { ?>
                <option <?php echo ($usuario->idperfil == $perfil->id) ? 'selected' : '' ; ?> value="<?php echo $perfil->id; ?>" > <?php echo $perfil->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
    <div class="formulario__campo">    
        <label for="telefono" class="formulario__label">Teléfono</label>
        <input
            type = "tel"
            class = "formulario__input"
            id = "telefono"
            name="telefono"
            placeholder="Teléfono"   
            value ="<?php echo $usuario->telefono;?>"     
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
            value ="<?php echo $usuario->email;?>"     
        />
    </div> 
        <div class="formulario__campo">
            <label for="password" class="formulario__label">Password</label>
            <input
                type="password"
                class="formulario__input"
                placeholder="Tu Password"
                id="password"
                name="password"
                value ="<?php echo $usuario->password;?>"   
            />
        </div>
        <div class="formulario__campo">
            <label for="password2" class="formulario__label">Repetir Password</label>
            <input
                type="password"
                class="formulario__input"
                placeholder="Repite Tu Password"
                id="password2"
                name="password2"
                value ="<?php echo $usuario->password;?>"  
            />
        </div>
        <div class="formulario__campo">
            <label for="idestado" class="formulario__label">Estado</label>
            <select class="formulario__select-xl" id="idestado" name ="idestado">
            <option value="" >-Seleccionar-</option>
                <?php foreach($estados as $estado) { ?>
                    <option <?php echo ($usuario->idestado == $estado->id) ? 'selected' : '' ; ?> value="<?php echo $estado->id; ?>" > <?php echo $estado->nombre; ?> </option>
                <?php }?> 
            </select>
        </div>
</fieldset>