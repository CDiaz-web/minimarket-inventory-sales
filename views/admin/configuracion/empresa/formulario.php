<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion General</legend>

    <?php if(($empresa->logo_actual)!=='') { ?>            
        <div class="formulario__imagen">
            <picture>
                <source srcset="<?php echo $_ENV['HOST'] . '/img/' . $empresa->logo; ?>.webp" type="image/webp">
                <source srcset="<?php echo $_ENV['HOST'] . '/img/' . $empresa->logo; ?>.png" type="image/png">
                <img src="<?php echo $_ENV['HOST'] . '/img/' . $empresa->logo; ?>.png" alt="Imagen Logo">
            </picture>
        </div>

    <?php } ?>    

    <div class="formulario__campo">
        <label for="logo" class="formulario__label">Logo</label>
        <input
            type = "file"
            class = "formulario__input formulario__input--file"
            id = "logo"
            name="logo"
        />
    </div>  

    <div class="formulario__campo">  
        <label for="ruc" class="formulario__label">Ruc</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "ruc"
            name="ruc"
            placeholder="Ruc de la Empresa"   
            value ="<?php echo $empresa->ruc;?>"     
        /> 
    </div>  
    <div class="formulario__campo">   
        <label for="nombre" class="formulario__label">Razón Social</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "nombre"
            name="nombre"
            placeholder="Nombre Producto"   
            value ="<?php echo $empresa->nombre;?>"     
        />
    </div> 
    <div class="formulario__campo">   
        <label for="direccion" class="formulario__label">Dirección</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "direccion"
            name="direccion"
            placeholder="Nombre Producto"   
            value ="<?php echo $empresa->direccion;?>"     
        />
    </div>
    <div class="formulario__campo">   
        <label for="email" class="formulario__label">E-Mail</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "email"
            name="email"
            placeholder="E-Mail de la Empresa"   
            value ="<?php echo $empresa->email;?>"     
        />
    </div> 
    <div class="formulario__campo">
        <label for="idmoneda" class="formulario__label">Moneda Base</label>
        <select class="formulario__select-xl" id="idmoneda" name ="idmoneda">
        <option value="" >-Seleccionar-</option>
            <?php foreach($monedas as $moneda) { ?>
                <option <?php echo ($empresa->idmoneda == $moneda->id) ? 'selected' : '' ; ?> value="<?php echo $moneda->id; ?>" > <?php echo $moneda->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
    <div class="formulario__campo">
        <label for="idtipo_pago" class="formulario__label">Tipo de Pago por Defecto</label>
        <select class="formulario__select-xl" id="idtipo_pago" name ="idtipo_pago">
        <option value="" >-Seleccionar-</option>
            <?php foreach($tipos as $tipo) { ?>
                <option <?php echo ($empresa->idtipo_pago == $tipo->id) ? 'selected' : '' ; ?> value="<?php echo $tipo->id; ?>" > <?php echo $tipo->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
    <div class="formulario__campo">   
            <label for="porcentaje_imp" class="formulario__label">IGV(%)</label>
            <input
                type="number" 
                class = "formulario__input"
                id = "porcentaje_imp"
                name="porcentaje_imp"  
                placeholder="0.00"   
                value ="<?php echo $empresa->porcentaje_imp;?>"                                   
            />
    </div>
    <div class="formulario__campo formulario__campo--check"> 
        <label for="validar_tc" class="formulario__check">
            <input
                type = "checkbox"
                class = "formulario__input"
                id = "validar_tc"
                name="validar_tc"   
                <?php if ($empresa->validar_tc == 1) echo 'checked="checked"'; ?> 
            /> Valida Tipo de Cambio
        </label>
    </div> 
      <div class="formulario__campo">   
            <label for="variaciontc" class="formulario__label">Variacion TC Mer.</label>
            <input
                type="number" 
                class = "formulario__input"
                id = "variaciontc"
                name="variaciontc"  
                placeholder="0.00"   
                step="0.01"
                min="0"                    
                value ="<?php echo number_format($empresa->variaciontc, 2, '.', ''); ?>"                                 
            />
    </div>
    <div class="formulario__campo formulario__campo--check"> 
        <label for="ov_requiere_aprobacion" class="formulario__check">
            <input
                type = "checkbox"
                class = "formulario__input"
                id = "ov_requiere_aprobacion"
                name="ov_requiere_aprobacion"   
                <?php if ($empresa->ov_requiere_aprobacion == 1) echo 'checked="checked"'; ?> 
            /> Ord. Venta Requiere Aprobacion
        </label>
    </div> 
    <div class="formulario__campo formulario__campo--check"> 
        <label for="oc_requiere_aprobacion" class="formulario__check">
            <input
                type = "checkbox"
                class = "formulario__input"
                id = "oc_requiere_aprobacion"
                name="oc_requiere_aprobacion"   
                <?php if ($empresa->oc_requiere_aprobacion == 1) echo 'checked="checked"'; ?> 
            /> Ord. Compra Requiere Aprobacion
        </label>
    </div> 
</fieldset>