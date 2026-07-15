
<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>

<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link" href="/admin/mantenimiento/productos/productos/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
            <button class="boton boton--primary"                  
                data-action="exportTable"
                data-table="tablaProductos"
                data-file="productos.xlsx"
                data-sheet="Productos"
            >
                Exportar
            </button>

            <a class="boton boton--primary-link" href="/admin/mantenimiento/productos/productos/cargar">
                <i class="fa-solid fa-circle-down"></i>
                Carga Masiva
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Producto..."
                data-table-search="tablaProductos"
            />
        </div>
    </div>  

    <div class="table-body">
        <?php if(!empty($productos)) { ?>
            <table id="tablaProductos"  class="table" data-table data-page-size="10">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Codigo</th> 
                        <th scope='col' class="table__th">Categoria</th>            
                        <th scope='col' class="table__th">Nombre</th>                    
                        <th scope='col' class="table__th">Unidad</th>                         
                        <th scope='col' class="table__th">Venta</th>                         
                        <th scope='col' class="table__th">Estado</th>                       
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($productos as $producto) { ?>
                        <tr class="table__tr <?= !$producto->activo ? 'fila--inactiva' : '' ?>">
                            <td class="table__td">
                                <?php echo  $producto->codigo ;?>
                            </td>   
                            <td class="table__td">
                                <?php echo $producto->categoria ;?>
                            </td>   
                            <td class="table__td">
                                <?php echo $producto->nombre ;?>
                            </td>  
                            <td class="table__td">
                                <?php echo $producto->unidad ;?>
                            </td>                                     
                            <td class="table__td">                           
                                <?php echo number_format($producto->venta, 2, '.', ''); ?> 
                            </td>                                               
                            
                            <td class="table__td">
                                <label class="switch">
                                    <input 
                                        type="checkbox"
                                        class="js-switch-ajax"
                                        data-id="<?= $producto->id ?>"
                                        data-modelo="Productos"
                                        <?= $producto->activo ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </td>                                 

                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a class="boton boton--primary" href="/admin/mantenimiento/productos/productos/editar?id=<?php echo $producto->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>                                
                                    </a>

                                    <form id ="frEliminar<?php echo $producto->id; ?>"  method="POST" action="/admin/mantenimiento/productos/productos/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $producto->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $producto->id; ?>"
                                        >
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center">No hay Productos Registradas</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaProductos"></div>  
    </div>
    
</div>


