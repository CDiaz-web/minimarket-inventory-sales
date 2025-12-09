
<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>
<div class="dashboard__contenedor">
    <!-- <div class="dashboard__contendor-boton"> -->


    <button class="dashboard__boton-exportar"  
            onclick="exportarTablaXLSX('tabla', 'productostienda.xlsx', 'Productos por Tienda')">
        Exportar
    </button>

    <input
        type = "text"
        class = "dashboard__buscar"
        id="buscar"
        name="buscar"
        placeholder="Buscar..."                        
    /> 
    <!-- </div> -->
</div>

<br class="">

<div class="dashboard__contenedor"  id="table_box_master">
    <?php if(!empty($tiendaproductos)) { ?>
        <table class="table" id="tabla" border="0">
            <thead class="table__thead">
                <tr>
                    <th scope='col' class="table__th">Código</th>
                    <th scope='col' class="table__th">Categoría</th>
                    <th scope='col' class="table__th">Nombre</th>
                    <th scope='col' class="table__th">Unidad</th>
                    <th scope='col' class="table__th">Stock Actual</th>
                    <th scope='col' class="table__th">Stock Comprometido</th>
                    <th scope='col' class="table__th">Stock Min.</th>
                    <th scope='col' class="table__th">Stock Max</th>
                    <th scope='col' class="table__th">Venta</th>
                    <th scope='col' class="table__th">Semáforo</th>
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody">
                <?php foreach($tiendaproductos as $tiendaproducto) { 
                    $stock = $tiendaproducto->stock_actual;
                    $min = $tiendaproducto->stock_min;
                    $max = $tiendaproducto->stock_max;

                    // Lógica semáforo
                    if ($stock < $min) {
                        $color = "red"; // Bajo stock
                        $tooltip = "Stock por debajo del mínimo";
                    } elseif ($stock > $max) {
                        $color = "blue"; // Sobre stock
                        $tooltip = "Stock por encima del máximo";
                    } else {
                        $color = "green"; // Correcto
                        $tooltip = "Stock dentro del rango";
                    }
                ?>
                    <tr class="table__tr">
                        <td class="table__td"><?= $tiendaproducto->codigo ?></td>
                        <td class="table__td"><?= $tiendaproducto->categoria ?></td>
                        <td class="table__td"><?= $tiendaproducto->nombre ?></td>
                        <td class="table__td"><?= $tiendaproducto->unidad ?></td>
                        <td class="table__td"><?= number_format($stock, 2) ?></td>
                        <td class="table__td"><?= number_format($tiendaproducto->stock_comprometido, 2) ?></td>
                        <td class="table__td"><?= number_format($min, 2) ?></td>
                        <td class="table__td"><?= number_format($max, 2) ?></td>
                        <td class="table__td"><?= number_format($tiendaproducto->venta, 2) ?></td>
                        <td class="table__td" title="<?= $tooltip ?>">
                            <i class="fa-solid fa-circle" style="color: <?= $color ?>; font-size:1.2em;"></i>
                        </td>
                        <td class="table__td--acciones">

                            <a href="#"
                            class="table__mantenimiento table__mantenimiento--editar btn-editar"
                            data-id="<?= $tiendaproducto->id ?>"
                            data-min="<?= $tiendaproducto->stock_min ?>"
                            data-max="<?= $tiendaproducto->stock_max ?>">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
       

    <?php } else { ?>
        <p class="text-center">No hay Productos en Tienda</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>
