<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>


<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link" href="/admin/configuracion/devolucion/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Tipos de Devolucion..."
                data-table-search="tablaDevolucion"
            />
        </div>
    </div>  






    <div class="table-body">
        <?php if(!empty($devoluciones)) { ?>
            <table id="tablaDevolucion"  class="table" data-table data-page-size="5">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Descripcion</th>                 
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($devoluciones as $devolucion) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $devolucion->nombre ;?>
                            </td>          
                                                      
                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a class="boton boton--primary" href="/admin/configuracion/devolucion/editar?id=<?php echo $devolucion->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>                                
                                    </a>

                                    <form id ="frEliminar<?php echo $devolucion->id; ?>"  method="POST" action="/admin/configuracion/devolucion/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $devolucion->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $devolucion->id; ?>"
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
            <p class="text-center">No hay tiendas Registradas</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaDevolucion"></div>  
    </div>
    
</div>

