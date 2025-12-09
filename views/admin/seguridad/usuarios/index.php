<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>
<div class="dashboard__contenedor">
    <!-- <div class="dashboard__contendor-boton"> -->

    <a class="dashboard__boton-agregar" href="/admin/seguridad/usuarios/crear">
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
    <?php if(!empty($usuarios)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>  
                    <th scope='col' class="table__th">Usuario</th>
                    <th scope='col' class="table__th">Perfil</th>
                    <!-- <th scope='col' class="table__th">E-Mail</th> -->
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($usuarios as $usuario) { ?>
                    <tr class="table__tr"> 
                        <td class="table__td">
                            <?php echo $usuario->usuario ;?>
                        </td>  
                        <td class="table__td">
                            <?php echo $usuario->perfil ;?>
                        </td>     
                        <!-- <td class="table__td">
                            <?php echo $usuario->email ;?>
                        </td>                       -->
                        <td class="table__td--acciones">
                            <a class="table__mantenimiento table__mantenimiento--editar" title="Editar Usuario" href="/admin/seguridad/usuarios/editar?id=<?php echo $usuario->id ?>">
                                <i class="fa-solid fa-user-pen"></i>                                
                            </a>

                            <a class="table__mantenimiento table__mantenimiento--editar" title="Asignar Tienda" href="/admin/seguridad/usuarios/tiendas?id=<?php echo $usuario->id ?>">
                                <i class="fa-solid fa-store"></i>                                
                            </a>

                            <form id ="frEliminar<?php echo $usuario->id; ?>"  method="POST" action="/admin/seguridad/usuarios/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $usuario->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $usuario->id; ?>">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Usuarios</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>