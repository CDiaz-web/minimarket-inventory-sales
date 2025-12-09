<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>
<div class="dashboard__contenedor">

    <a class="dashboard__boton-agregar" href="/admin/configuracion/correlativos/crear">
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
    <?php if(!empty($correlativos)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>        
                    <th scope='col' class="table__th">Id</th>  
                    <th scope='col' class="table__th">Tienda</th>   
                    <th scope='col' class="table__th">Tipo Documento</th>                  
                    <th scope='col' class="table__th">Ultimo Correlativo</th>                  
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($correlativos as $correlativo) { ?>
                    <tr class="table__tr">     
                        <td class="table__td">
                            <?php echo $correlativo->id ;?>
                        </td>          
                        <td class="table__td">
                            <?php echo $correlativo->nombre;?>
                        </td>    
                        <td class="table__td">
                            <?php echo $correlativo->tipo_documento ;?>
                        </td>  
                        <td class="table__td">
                            <?php echo $correlativo->ultimo_numero ;?>
                        </td>                  
                        <td class="table__td--acciones">
                            <form id ="frEliminar<?php echo $correlativo->id; ?>"  method="POST" action="/admin/configuracion/correlativos/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $correlativo->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $correlativo->id; ?>">
                                    <i class="fa-solid fa-circle-xmark  table____mantenimient--icono"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Correlativos</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>