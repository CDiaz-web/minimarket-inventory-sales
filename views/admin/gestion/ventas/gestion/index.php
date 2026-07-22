<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../../templates/alertas.php';          
    ?>

<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">

            <form method="GET" class="dashboard__filtros"> 
                <label class="formulario__label">Desde</label>
                <input type="date" name="fecha_inicial"  value="<?= $filtros['fecha_inicial'] ?>" class="formulario__input">
                <label class="formulario__label">Hasta</label>
                <input type="date" name="fecha_final" value="<?= $filtros['fecha_final'] ?>" class="formulario__input">

                <button type="submit" class="boton boton--primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                </button>
            </form>       

        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar..."
                data-table-search="tablaOrdenes"
            />
        </div>
    </div>  

    <div class="table-body">
        <?php if(!empty($ordenes)) { ?>
            <table id="tablaOrdenes"  class="table" data-table data-page-size="20">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Numero</th>  
                        <th scope='col' class="table__th">Cliente</th>     
                        <th scope='col' class="table__th">Fecha</th>  
                        <th scope='col' class="table__th">Moneda</th>   
                        <th scope='col' class="table__th">Subtotal</th> 
                        <th scope='col' class="table__th">IGV</th>  
                        <th scope='col' class="table__th">Total</th> 
                        <th scope='col' class="table__th">Estado</th>                             
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tablaOrdenes">
                    <?php foreach($ordenes as $orden) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $orden->numero;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $orden->cliente;?>
                            </td>
                            <td class="table__td">
                                <?php echo $orden->fecha;?>
                            </td>  
                            <td class="table__td">
                                <?php echo $orden->simbolo;?>
                            </td>  
                            <td class="table__td">
                                <?php echo number_format($orden->subtotal_origen, 2, '.', ''); ?>
                            </td>
                            <td class="table__td">
                                <?php echo number_format($orden->igv_origen, 2, '.', ''); ?>
                            </td>     
                            <td class="table__td">
                                <?php echo number_format($orden->total_origen, 2, '.', ''); ?>
                            </td>    
                            <td class="table__td">
                                <?php echo $orden->estado;?>
                            </td>                                
                            <td class="table__col-actions" >
                                <div class="table__acciones">

                                    <!-- Editar -->

                                    <a href="/admin/gestion/ventas/orden?id=<?= $orden->id ?>"
                                        class="boton boton--primary btn-editar-venta"
                                        data-id="<?= $orden->id; ?>"
                                        data-estado="<?= $orden->estado; ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <!-- Aprobar -->
                                    <button 
                                        class="boton boton--success-light  btn-aprobar"
                                        data-id="<?= $orden->id ?>"
                                        data-estado="<?= $orden->estado ?>">
                                        <i class="fa-solid fa-check"></i>
                                    </button>

                                    <!-- Imprimir -->
                                    <a 
                                        href="/admin/gestion/ventas/orden/imprimir?id=<?= $orden->id ?>" 
                                        target="_blank"
                                        class="boton boton--primary" >
                                        <i class="fa fa-print"></i>
                                    </a>

                                    <!-- Anular -->
                                    <button 
                                        class="boton boton--danger btn-anular"
                                        type="button"                                        
                                        data-id="<?= $orden->id ?>"
                                        data-estado="<?= $orden->estado ?>">
                                       
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
       
                                </div>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center">No hay Información Registrada</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaOrdenes"></div>  
    </div>
    
</div>

