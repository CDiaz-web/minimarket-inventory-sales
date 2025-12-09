<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>
<div class="dashboard__contenedor">
    <!-- <div class="dashboard__contendor-boton"> -->
    <a class="dashboard__boton" href="/admin/seguridad/usuarios">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
    <a class="dashboard__boton-agregar" href="/admin/seguridad/usuarios/tiendas/crear?id=<?php echo $_GET['id'] ?>">   
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
    <!-- </div> -->
</div>

<br class="">

<div class="dashboard__contenedor"  id="table_box_master">
    <?php if(!empty($tiendas)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>  
                    <th scope='col' class="table__th">Tienda</th>
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($tiendas as $tienda) { ?>
                    <tr class="table__tr"> 
                        <td class="table__td">
                            <?php echo $tienda->tienda ;?>
                        </td>                                          
                        <td class="table__td--acciones">

                            <form id ="frEliminar<?php echo $tienda->id; ?>"  method="POST" action="/admin/seguridad/usuarios/tiendas/eliminar?id=<?php echo $tienda->id; ?>" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $tienda->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $tienda->id; ?>">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Tiendas</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>