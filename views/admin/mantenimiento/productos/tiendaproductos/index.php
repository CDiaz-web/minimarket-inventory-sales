
<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>




<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <button class="boton boton--primary"                  
                data-action="exportTable"
                data-table="tablaProductosTiendas"
                data-file="productostienda.xlsx"
                data-sheet="Productos por Tienda"
            >
                Exportar
            </button>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Productos..."
                data-table-search="tablaProductosTiendas"
            />
        </div>
    </div>  


    <div class="table-body">
        <?php if(!empty($tiendaproductos)) { ?>
            <table id="tablaProductosTiendas"  class="table" data-table data-page-size="10">
                <thead class="table thead">
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
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
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
                            <td class="table__acciones">

                                <a href="#"
                                class="boton boton--primary btn-editar"
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
            <p class="text-center">No hay tiendas Registradas</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaProductosTiendas"></div>  
    </div>
    
</div>

