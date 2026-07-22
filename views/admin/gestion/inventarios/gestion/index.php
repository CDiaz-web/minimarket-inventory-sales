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
                data-table-search="tablaMovimientos"
            />
        </div>
    </div>  


    <div class="table-body">
        <?php if(!empty($inventarios)) { ?>
            <table id="tablaMovimientos"  class="table" data-table data-page-size="20">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Numero</th>                           
                        <th scope='col' class="table__th">Fecha</th>  
                        <th scope='col' class="table__th">Tienda</th>   
                        <th scope='col' class="table__th">Movimiento</th> 
                        <th scope='col' class="table__th">Tienda Destino</th>  
                        <th scope='col' class="table__th">Observacion</th> 
                        <th scope='col' class="table__th">Estado</th>                             
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tablaMovimientos">
                    <?php foreach($inventarios as $inventario) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $inventario->documento;?>
                            </td>                 
                            <td class="table__td">
                                <?php echo $inventario->fecha;?>
                            </td>  
                            <td class="table__td">
                                <?php echo $inventario->tienda_origen;?>
                            </td>  
                            <td class="table__td">
                                <?php echo $inventario->movimiento;?>
                            </td>
                            <td class="table__td">
                                <?php echo $inventario->tienda_destino;?>
                            </td>     
                            <td class="table__td">
                                <?php echo $inventario->observacion;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $inventario->estado;?>
                            </td>                                
                            <td class="table__col-actions" >
                                <div class="table__acciones"> 

                                    <!-- Imprimir -->
                                    <a 
                                        href="/admin/gestion/inventarios/movimiento/imprimir?id=<?= $inventario->id ?>" 
                                        target="_blank"
                                        class="boton boton--primary" >
                                        <i class="fa fa-print"></i>
                                    </a>

                                    <!-- Anular -->
                                    <button 
                                        class="boton boton--danger btn-anular-mov"
                                        type="button"                                        
                                        data-id="<?= $inventario->id ?>"
                                        data-estado="<?= $inventario->estado ?>"
                                        data-es_generado="<?= $inventario->es_generado ?>">
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
        <div class="table-pagination" data-table-pagination="tablaMovimientos"></div>  
    </div>
    
</div>
