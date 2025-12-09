<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>
<div class="dashboard__contenedor">
    <!-- <div class="dashboard__contendor-boton"> -->

    <a class="dashboard__boton-agregar" href="/admin/seguridad/perfiles/crear">
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
    <?php if(!empty($perfiles)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>  
                    <th scope='col' class="table__th">Nombre</th>
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($perfiles as $perfil) { ?>
                    <tr class="table__tr"> 
                        <td class="table__td">
                            <?php echo $perfil->nombre ;?>
                        </td>  
                        
                        <td class="table__td--acciones">
                            <a class="table__mantenimiento table__mantenimiento--editar" href="/admin/seguridad/perfiles/editar?id=<?php echo $perfil->id ?>">
                                <i class="fa-solid fa-user-pen"></i>                                
                            </a>
                            <a class="table__mantenimiento table__mantenimiento--editar" href="/admin/seguridad/perfiles/opciones?id=<?php echo $perfil->id ?>">
                                <i class="fa-solid fa-list-check"></i>                               
                            </a>
                            <form id ="frEliminar<?php echo $perfil->id; ?>"  method="POST" action="/admin/seguridad/perfiles/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $perfil->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $perfil->id; ?>">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Periles</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>
