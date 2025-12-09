<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>
<div class="dashboard__contenedor">

    <a class="dashboard__boton-agregar" href="/admin/configuracion/unidad/crear">
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
    <?php if(!empty($unidades)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>      
                    <th scope='col' class="table__th">Codigo</th>    
                    <th scope='col' class="table__th">Descripcion</th>                               
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($unidades as $unidad) { ?>
                    <tr class="table__tr">            
                        <td class="table__td">
                            <?php echo $unidad->codigo ;?>
                        </td>    
                        <td class="table__td">
                            <?php echo $unidad->nombre;?>
                        </td>                  
                        <td class="table__td--acciones">
                            <a class="table__mantenimiento table__mantenimiento--editar" href="/admin/configuracion/unidad/editar?id=<?php echo $unidad->id ?>">
                                <i class="fa-solid fa-user-pen  table____mantenimient--icono"></i>                                
                            </a>

                            <form id ="frEliminar<?php echo $unidad->id; ?>"  method="POST" action="/admin/configuracion/unidad/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $unidad->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $unidad->id; ?>">
                                    <i class="fa-solid fa-circle-xmark  table____mantenimient--icono"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Unidad de Medida Registrada</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>