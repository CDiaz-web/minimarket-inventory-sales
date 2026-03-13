<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>


<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link " href="/admin/mantenimiento/clientes/clasificacion/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Clasificacion..."
                data-table-search="tablaClasificacion"
            />
        </div>
    </div>  


    <div class="table-body">
        <?php if(!empty($tipos)) { ?>
            <table id="tablaClasificacion"  class="table" data-table data-page-size="5">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Codigo</th>    
                        <th scope='col' class="table__th">Descripcion</th>                
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tablaClasificacion">
                    <?php foreach($tipos as $tipo) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $tipo->codigo ;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $tipo->nombre;?>
                            </td>                                 
                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a class="boton boton--primary" href="/admin/mantenimiento/clientes/clasificacion/editar?id=<?php echo $tipo->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>                                
                                    </a>

                                    <form id ="frEliminar<?php echo $tipo->id; ?>"  method="POST" action="/admin/mantenimiento/clientes/clasificacion/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $tipo->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $tipo->id; ?>"
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
            <p class="text-center">No hay Clasificacion Registradas</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaClasificacion"></div>  
    </div>
    
</div>












