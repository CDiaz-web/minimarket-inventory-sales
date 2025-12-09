
<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>
<div class="dashboard__contenedor">
    <!-- <div class="dashboard__contendor-boton"> -->

    <a class="dashboard__boton-agregar" href="/admin/mantenimiento/productos/productos/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir
    </a>
    <button class="dashboard__boton-exportar"  
            onclick="exportarTablaXLSX('tabla', 'productos.xlsx', 'Productos')">
        Exportar
    </button>
    <a class="dashboard__boton"  href="/admin/mantenimiento/productos/productos/cargar">
        <i class="fa-solid fa-circle-down"></i>
        Carga Masiva
    </a>
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
    <?php if(!empty($productos)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>   
                    <th scope='col' class="table__th">Codigo</th> 
                    <th scope='col' class="table__th">Categoria</th>            
                    <th scope='col' class="table__th">Nombre</th>                    
                    <th scope='col' class="table__th">Unidad</th> 
                    <th scope='col' class="table__th">Costo</th> 
                    <th scope='col' class="table__th">Venta</th> 
                    <th scope='col' class="table__th">Utilidad</th> 
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($productos as $producto) { ?>
                    <tr class="table__tr">
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
                            <?php echo number_format($producto->costo, 2, '.', ''); ?>
                        </td>  
                        <td class="table__td">                           
                            <?php echo number_format($producto->venta, 2, '.', ''); ?> 
                        </td>                 
                        <td class="table__td">                            
                            <?php echo number_format($producto->utilidad, 2, '.', ''); ?>
                        </td>   
                        <td class="table__td--acciones">
                            <a class="table__mantenimiento table__mantenimiento--editar" href="/admin/mantenimiento/productos/productos/editar?id=<?php echo $producto->id ?>">
                                <i class="fa-solid fa-user-pen"></i>                                
                            </a>
                            <form id ="frEliminar<?php echo $producto->id; ?>"  method="POST" action="/admin/mantenimiento/productos/productos/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $producto->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $producto->id; ?>">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Productos</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>
