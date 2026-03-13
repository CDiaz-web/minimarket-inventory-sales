<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>


<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link " href="/admin/gestion/logistica/inventario">
                <i class="fa-solid fa-circle-plus"></i>
                Volver
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar ..."
                data-table-search="tablaClasificacion"
            />
        </div>
    </div>  


    <div class="table-body">
        <?php if(!empty($productos)) { ?>
            <table id="tablaClasificacion"  class="table" data-table data-page-size="10">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Codigo</th>    
                        <th scope='col' class="table__th">Producto</th>                
                        <th scope='col' class="table__th">Cantidad</th>
                        <th scope='col' class="table__th">Costo</th>                                
                        <th scope='col' class="table__th">Total</th>                                
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tablaClasificacion">
                    <?php foreach($productos as $producto) { ?>
                        <tr class="table__tr">
                            <form method="POST">
                            <td class="table__td">
                                <?php echo $producto->codigo ;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $producto->nombre;?>
                            </td>         
                            <td class="table__td">  
                                <input 
                                    type="number" 
                                    name="cantidad"
                                    class="formulario__input" 
                                    value ="<?php echo number_format($producto->cantidad, 2, '.', ''); ?>" min="1"  
                                >
                            </td>       
                            <td class="table__td">  
                                <input 
                                    type="number" 
                                    name="costo"
                                    class="formulario__input" 
                                    value ="<?php echo number_format($producto->costo, 2, '.', ''); ?>" 
                                    min="0.00"  
                                >
                            </td>      
                            <td class="table__td">   
                                <input type="number" class="formulario__input" value ="<?php echo number_format($producto->total, 2, '.', ''); ?>" min="0.00" disabled  >
                            </td>                       
                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <button class="boton boton--primary">
                                        <i class="fa-solid fa-floppy-disk"></i>                              
                                    </button>

                                    <!-- <form id ="frEliminar<?php echo $tipo->id; ?>"  method="POST" action="/admin/mantenimiento/clientes/clasificacion/eliminar" class="table__formulario"> -->
                                        <input type="hidden" name="id" value="<?php echo $tipo->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $tipo->id; ?>"
                                        >
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </button>
                                    <!-- </form> -->
                                </div>
                                <input type="hidden" name="iddetalle" value="<?php echo $producto->iddetalle; ?>">
                            </td>
                            </form>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center">No hay Registros</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaClasificacion"></div>  
    </div>
    
</div>