<form method="POST" id="form-inventario" class="formulario">
    <!-- === CABECERA === -->
    <fieldset class="formulario__fieldset">
        <legend class="formulario__legend">Cabecera</legend>

        <div class="formulario__campo">
            <label for="codigotipo" class="formulario__label">Tipo de Movimiento</label>
            <select name="cabecera[codigotipo]" id="codigotipo" required>
                <option value="">-- Seleccionar --</option>
                <?php foreach($tiposmovimiento as $tipo): ?>
                    <option  value="<?= $tipo->codigo ?>">         
                        <?= $tipo->nombre ?> 
                    </option>        
                <?php endforeach; ?>
            </select>
        </div>

        <div class="formulario__campo">
            <label for="fecha" class="formulario__label">Fecha</label>
            <input type="date" id="fecha" name="cabecera[fecha]" value="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="formulario__campo">
            <label for="observacion" class="formulario__label">Observación</label>
            <textarea id="observacion" name="cabecera[observacion]"></textarea>
        </div>
        <div class="formulario__campo" id="campo-tienda-destino"  style="display:none;">
            <label for="idtienda_relacion" class="formulario__label">Tienda</label>
            <select class="formulario__select-xl" name="cabecera[idtienda_relacion]" id="idtienda_relacion" name ="idtienda_relacion">
            <option value="" >-Seleccionar-</option>
                <?php foreach($tiendas as $tienda) { ?>
                    <option  value="<?php echo $tienda->id; ?>" > <?php echo $tienda->nombre; ?> </option>
                <?php }?> 
            </select>
        </div>
    </fieldset>

    <!-- === DETALLE === -->
    <fieldset class="formulario__fieldset">
        <legend class="formulario__legend">Detalle</legend>

        <table class="table" id="tabla-detalle">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Unidad</th>
                    <th>Cantidad</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- Filas dinámicas -->
            </tbody>
        </table>

        <button type="button" id="btn-agregar-detalle">➕ Agregar producto</button>
    </fieldset>

    <!-- === BOTONES === -->
    <div class="formulario__acciones">
        <input type="submit" value="Guardar" class="btn btn-primario">
        <a href="/admin/logistica/gestion/inventario" class="btn btn-secundario">Cancelar</a>
    </div>
</form>

<script>
    window.productosOptions = `
        <?php foreach($productos as $p): ?>
            <option value="<?= $p->id ?>" data-unidad="<?= $p->unidad ?>">
                <?= $p->codigo . ' - ' . $p->nombre ?>
            </option>
        <?php endforeach; ?>
    `;
</script>
