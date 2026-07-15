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
                data-file="detalleventas.xlsx"
                data-sheet="Ventas"
            >
                Exportar xls
            </button>
            <form method="POST" action="/admin/gestion/reportes/pdffecha" target="_blank">
        
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
<!-- resumen del reporte -->
    <?php if($resumen) { ?>
        <div class="reporte-resumen">

            <div class="resumen-card">
                <span class="resumen-titulo">Ventas</span>
                <span class="resumen-valor"><?= $resumen->cantidad_ordenes ?></span>
            </div>

            <div class="resumen-card">
                <span class="resumen-titulo">Subtotal</span>
                <span class="resumen-valor">$ <?= number_format($resumen->subtotal_vendido,2) ?></span>
            </div>

            <div class="resumen-card">
                <span class="resumen-titulo">IGV</span>
                <span class="resumen-valor">$ <?= number_format($resumen->total_igv,2) ?></span>
            </div>

            <div class="resumen-card resumen-total">
                <span class="resumen-titulo">Total Vendido</span>
                <span class="resumen-valor">$ <?= number_format($resumen->total_general,2) ?></span>
            </div>

        </div>
    <?php } ?>
<!--  -->
    <div class="table-body">
        <?php if(!empty($ventas)) { ?>
            <table id="tabla"  class="table" data-table data-page-size="10">
                <thead class="table thead">
                    <tr>               
                        <th>Número</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Subtotal</th>
                        <th>Igv</th>
                        <th>Total</th>
                        <th>Aprobado por</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($ventas as $row) { ?>
                        <tr class="table__tr">
                            <td><?= $row->numero ?></td>
                            <td><?= $row->fechaapro ?></td>
                            <td><?= $row->cliente ?></td>
                            <td><?= number_format($row->subtotal,2) ?></td>
                            <td><?= number_format($row->igv,2) ?></td>
                            <td><?= number_format($row->total,2) ?></td>
                            <td><?= $row->aprobado_por ?></td>
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
















