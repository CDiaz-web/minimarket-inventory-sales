<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../../templates/alertas.php';          
    ?>

<div class="table-wrapper">    

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link" href="/admin/gestion/reportes">
                <i class="fa-solid fa-circle-arrow-left"></i>
                Volver
            </a>  
            <button class="boton boton--success-light"                  
                data-action="exportTable"
                data-table="tabla"
                data-file="ventasproductos.xlsx"
                data-sheet="Ventas Productos"
            >
                Exportar xls
            </button>
            <form method="POST" action="/admin/gestion/reportes/pdfproductos" target="_blank">
        
                <input type="hidden" name="fecha_inicial"  value="<?= $filtros['fecha_inicial'] ?>">
                <input type="hidden" name="fecha_final" value="<?= $filtros['fecha_final'] ?>">

                <button type="submit" class="boton boton--danger">
                    Exportar PDF
                </button>
            </form>   
        </div>
        <div class="table-actions">
 
            <form method="GET" action="/admin/gestion/reportes/fecha" class="dashboard__filtros">      

                <div class="formulario__campo">
                    <label class="formulario__label">Fecha Inicial</label>
                    <input 
                        type="date"
                        name="fecha_inicial"
                        value="<?= $filtros['fecha_inicial'] ?? '' ?>"
                        required
                    >
                </div>

                <div class="formulario__campo">
                    <label class="formulario__label">Fecha Final</label>
                    <input 
                        type="date"
                        name="fecha_final"
                        value="<?= $filtros['fecha_final'] ?? '' ?>"
                        required
                    >
                </div>

                <input type="submit" value="Filtrar" class="boton boton--filtrar">
            </form>           
        </div>

    </div>     



    <div class="table-body">
        <?php if(!empty($ventas)) { ?>
            <table id="tabla"  class="table" data-table data-page-size="10">
                <thead class="table thead">
                    <tr>               
                        <th>Codigo</th>               
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Igv</th>
                        <th>Total</th>
                        <th>Precio Promedio</th>      
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($ventas as $row) { ?>
                        <tr class="table__tr">
                            <td><?= $row->codigo ?></td>                   
                            <td><?= $row->producto ?></td>
                            <td><?= number_format($row->cantidad_vendida,2) ?></td>
                            <td><?= number_format($row->subtotal_vendido,2) ?></td>
                            <td><?= number_format($row->total_igv,2) ?></td>
                            <td><?= number_format($row->total_general,2) ?></td>
                            <td><?= number_format($row->precio_promedio,2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center">No hay Información</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tabla"></div>  
    </div>
    
</div>





