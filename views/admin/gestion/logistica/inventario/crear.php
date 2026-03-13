<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<form method="POST" id="form-inventario" class="formulario">

<div class="table-wrapper">

    <!-- HEADER -->
    <div class="table-header">

        <div class="table-actions">
            <a class="boton boton--primary-link" href="/admin/gestion/logistica/inventario">
                <i class="fa-solid fa-circle-arrow-left"></i>
                Volver
            </a>
        </div>

    </div>

    <?php include_once __DIR__ . '/../../../../templates/alertas.php'; ?>

    <!-- CABECERA DEL MOVIMIENTO -->
    <div class="table-body">

        <div class="form-grid">

            <div class="formulario__campo">
                <label for="codigotipo" class="formulario__label">Tipo de Movimiento</label>
                <select name="cabecera[codigotipo]" id="codigotipo" class = "formulario__input" required>
                    <option value="">-- Seleccionar --</option>
                    <?php foreach($tiposmovimiento as $tipo): ?>
                        <option value="<?= $tipo->codigo ?>">
                            <?= $tipo->nombre ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="formulario__campo">
                <label for="fecha" class="formulario__label">Fecha</label>
                <input type="date" id="fecha" class = "formulario__input" name="cabecera[fecha]" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="formulario__campo form-grid-full">
                <label for="observacion" class="formulario__label">Observación</label>
                <textarea id="observacion" name="cabecera[observacion]" class = "formulario__input"></textarea>
            </div>

            <div class="formulario__campo" id="campo-tienda-destino" style="display:none;">
                <label class="formulario__label">Tienda</label>
                <select name="cabecera[idtienda_relacion]" id="idtienda_relacion" class = "formulario__input">
                    <option value="">-Seleccionar-</option>
                    <?php foreach($tiendas as $tienda) { ?>
                        <option value="<?php echo $tienda->id; ?>">
                            <?php echo $tienda->nombre; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

        </div>

    </div>

    <!-- TABLA DETALLE -->
    <div class="table-body">

        <table class="table" id="tabla-detalle">

            <thead class="table thead">
                <tr>
                    <th class="table__th">Producto</th>
                    <th class="table__th">Unidad</th>
                    <th class="table__th">Cantidad</th>
                    <th class="table__th">Costo</th>
                    <th class="table__th"></th>
                </tr>
            </thead>

            <tbody class="table-tbody">
                <!-- filas dinámicas -->
            </tbody>

        </table>

    </div>

    <!-- FOOTER -->
    <div class="table-footer">

        <div class="table-actions">

            <button type="button"
                class="boton boton--filtrar"
                id="btn-agregar-detalle">                
                Agregar producto
            </button>

            <input type="submit"
                value="Guardar"
                class="boton boton--primary">

            <a href="/admin/gestion/logistica/inventario"
               class="boton boton--danger-link">
                Cancelar
            </a>

        </div>

    </div>

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