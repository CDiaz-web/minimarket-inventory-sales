<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../../templates/alertas.php';          
    ?>
<div class="dashboard__contenedor">

    <a class="dashboard__boton-agregar" href="/admin/mantenimiento/clientes/clientes/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir
    </a>
    <!-- <a class="dashboard__boton-exportar"  id="download_xls" href="#">
        <i class="fa-solid fa-file-excel"></i>
        Exportar
    </a>  -->
    <button class="dashboard__boton-exportar"  
            onclick="exportarTablaXLSX('tabla', 'clientes.xlsx', 'Clientes')">
        Exportar
    </button>

    <a class="dashboard__boton"  href="/admin/mantenimiento/clientes/clientes/cargar">
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
</div>
<br>
<div class="dashboard__contenedor"  id="table_box_master">
    <?php if(!empty($clientes)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>
                    <th scope='col' class="table__th">Documento</th>        
                    <th scope='col' class="table__th">Nombre</th> 
                    <th scope='col' class="table__th">Clasificacion</th>                     
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($clientes as $cliente) { ?>
                    <tr class="table__tr">  
                        <td class="table__td">
                            <?php echo $cliente->documento ;?>
                        </td>              
                        <td class="table__td">
                            <?php echo $cliente->nombre_cliente ;?>
                        </td>  
                        <td class="table__td">
                            <?php echo $cliente->clasificacion ;?>
                        </td>                                             
                        <td class="table__td--acciones">
                            <a class="table__mantenimiento table__mantenimiento--editar" href="/admin/mantenimiento/clientes/clientes/editar?id=<?php echo $cliente->id ?>">
                                <i class="fa-solid fa-user-pen  table____mantenimient--icono"></i>                                
                            </a>
                            <form id ="frEliminar<?php echo $cliente->id; ?>"  method="POST" action="/admin/mantenimiento/clientes/clientes/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $cliente->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $cliente->id; ?>">
                                    <i class="fa-solid fa-circle-xmark  table____mantenimient--icono"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Clientes Registrados</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>

