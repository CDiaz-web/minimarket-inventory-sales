<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Series</legend> 

    <div class="formulario__campo">
        <label for="idtienda" class="formulario__label">Tienda</label>
        <select class="formulario__select-xl" id="idtienda" name ="idtienda">
        <option value="" >-Seleccionar-</option>
            <?php foreach($tiendas as $tienda) { ?>
                <option <?php echo ((int)$serie->idtienda === (int)$tienda->id) ? 'selected' : '' ; ?> value="<?php echo $tienda->id; ?>" > <?php echo $tienda->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>

    <div class="formulario__campo">
        <label for="idtipodocumento" class="formulario__label">Tipo Documento</label>
        <select class="formulario__select-xl" id="idtipodocumento" name ="idtipodocumento">
        <option value="" >-Seleccionar-</option>
            <?php foreach($documentos as $documento) { ?>
                <option <?php echo ((int)$serie->idtipodocumento === (int)$documento->id) ? 'selected' : '' ; ?> value="<?php echo $documento->id; ?>" > <?php echo $documento->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>

    <div class="formulario__campo">    
        <label for="serie" class="formulario__label">Serie</label>
        <input
            type = "text"
            class = "formulario__input"
            id = "serie"
            name="serie"
            placeholder="Series"   
            maxlength="4"
            value ="<?php echo $serie->serie;?>"     
        />
    </div>  

    <div class="formulario__campo">   
        <label for="cantidad_digitos" class="formulario__label">Digitos Correlativo</label>
        <input
            type="number" 
            class = "formulario__input"
            id = "cantidad_digitos"
            name="cantidad_digitos"  
            placeholder="0"   
            value ="<?php echo $serie->cantidad_digitos;?>"                                   
        />
    </div>

    <div class="formulario__campo">   
        <label for="ultimo_correlativo" class="formulario__label">Último Correlativo</label>
        <input
            type="number" 
            class = "formulario__input"
            id = "ultimo_correlativo"
            name="ultimo_correlativo"  
            placeholder="0"   
            value ="<?php echo $serie->ultimo_correlativo ?? 0 ;?>"                                            
        />
    </div>

    <div class="formulario__campo formulario__campo--check"> 
        <label for="predeterminado" class="formulario__check">
            <input
                type = "checkbox"
                class = "formulario__input"
                id = "predeterminado"
                name="predeterminado"   
                <?php if ($serie->predeterminado == 1) echo 'checked="checked"'; ?> 
            /> Serie Predeterminada
        </label>
    </div> 

    <div class="formulario__campo formulario__campo--check" data-switch-estado> 
        
        <span 
            class="switch__label"
            data-switch-label
        >  
            <?= (isset($serie) && $serie->id)  
                ? ($serie->activo ? 'Activo' : 'Inactivo') 
                : 'Activo' ?>
        </span>

        <label class="switch">
            <input
                type="checkbox"
                name="activo"
                <?= (isset($serie) && $serie->id) 
                    ? ($serie->activo ? 'checked' : '') 
                    : 'checked' ?>
            >
            <span class="slider"></span> 
        </label>       

    </div>
    
</fieldset>