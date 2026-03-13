<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Tipo de Cambio</legend>     
    <button type="button" id="btnTraerSunat" class="formulario__solicitar formulario__solicitar--registrar">Traer tipo de cambio SUNAT</button>    
    <div class="formulario__campo">    
        <label for="fecha" class="formulario__label">Fecha</label>
        <input
            type = "date"
            class = "formulario__input"
            id = "fecha"
            name="fecha"            
            value="<?php echo isset($factor->id) ? $factor->fecha : date('Y-m-d'); ?>"
            max="<?php echo date('Y-m-d'); ?>"    
        />
    </div>  

    <div class="formulario__campo">
        <label for="idmoneda_origen" class="formulario__label">Moneda Origen</label>
        <select class="formulario__select-xl" id="idmoneda_origen" name ="idmoneda_origen">
        <option value="" >-Seleccionar-</option>
            <?php foreach($monedas as $moneda) { ?>
                <option <?php echo ($factor->idmoneda_origen == $moneda->id) ? 'selected' : '' ; ?> value="<?php echo $moneda->id; ?>" > <?php echo $moneda->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>

    <div class="formulario__campo">
        <label for="idmoneda_destino" class="formulario__label">Moneda Destino</label>
        <select class="formulario__select-xl" id="idmoneda_destino" name ="idmoneda_destino">
        <option value="" >-Seleccionar-</option>
            <?php foreach($monedas as $moneda) { ?>
                <option <?php echo ($factor->idmoneda_destino == $moneda->id) ? 'selected' : '' ; ?> value="<?php echo $moneda->id; ?>" > <?php echo $moneda->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>


    <div class="formulario__campo">    
        <label for="compra_oficial" class="formulario__label">Compra Oficial</label>
        <input
            type = "number"
            class = "formulario__input  formulario__input--readonly"
            step="0.001"
            id = "compra_oficial"
            name="compra_oficial"     
            value ="<?php echo $factor->compra_oficial;?>"   
            readonly  
        />
    </div>   

    <div class="formulario__campo">    
        <label for="venta_oficial" class="formulario__label">Venta Oficial</label>
        <input
            type = "number"
            class = "formulario__input  formulario__input--readonly"
            step="0.001"
            id = "venta_oficial"
            name="venta_oficial"     
            value ="<?php echo $factor->venta_oficial;?>" 
            readonly    
        />
    </div> 

    <div class="formulario__campo">    
        <label for="compra_mercado" class="formulario__label">Compra Mercado</label>
        <input
            type = "number"
            class = "formulario__input"
            step="0.001"
            id = "compra_mercado"
            name="compra_mercado"     
            value ="<?php echo $factor->compra_mercado;?>"     
        />
    </div> 

    <div class="formulario__campo">    
        <label for="venta_mercado" class="formulario__label">Venta Mercado</label>
        <input
            type = "number"
            class = "formulario__input"
            step="0.001"
            id = "venta_mercado"
            name="venta_mercado"     
            value ="<?php echo $factor->venta_mercado;?>"     
        />
    </div> 
</fieldset>

