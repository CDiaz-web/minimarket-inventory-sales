<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>
<div class="dashboard__contenedor">

    <a class="dashboard__boton-agregar" href="/admin/configuracion/tiendas/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir
    </a>

    <input
        type = "text"
        class = "dashboard__buscar"
        id="buscar"
        name="buscar"
        placeholder="Buscar..."                        
    /> 
</div>
<br>
<div class="dashboard__contenedor"  id="table_box_master">
    <?php if(!empty($tiendas)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>        
                    <th scope='col' class="table__th">Código</th>  
                    <th scope='col' class="table__th">Nombre</th>   
                    <th scope='col' class="table__th">Dirección</th>                  
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($tiendas as $tienda) { ?>
                    <tr class="table__tr">     
                        <td class="table__td">
                            <?php echo $tienda->codigo ;?>
                        </td>          
                        <td class="table__td">
                            <?php echo $tienda->nombre ;?>
                        </td>    
                        <td class="table__td">
                            <?php echo $tienda->direccion ;?>
                        </td>                  
                        <td class="table__td--acciones">
                            <a class="table__mantenimiento table__mantenimiento--editar" href="/admin/configuracion/tiendas/editar?id=<?php echo $tienda->id ?>">
                                <i class="fa-solid fa-user-pen  table____mantenimient--icono"></i>                                
                            </a>

                            <form id ="frEliminar<?php echo $tienda->id; ?>"  method="POST" action="/admin/configuracion/tiendas/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $tienda->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $tienda->id; ?>">
                                    <i class="fa-solid fa-circle-xmark  table____mantenimient--icono"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Categorías</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>

