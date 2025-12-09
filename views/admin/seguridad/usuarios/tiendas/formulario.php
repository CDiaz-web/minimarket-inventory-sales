<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Tienda</legend> 
  

    <div class="formulario__campo">
        <label for="idtienda" class="formulario__label">Tiendas</label>
        <select class="formulario__select-xl" id="idtienda" name ="idtienda">
        <option value="" >-Seleccionar-</option>
            <?php foreach($tiendas as $tienda) { ?>
                <option  value="<?php echo $tienda->id; ?>" > <?php echo $tienda->nombre; ?> </option>
            <?php }?> 
        </select>
    </div>
   
</fieldset>