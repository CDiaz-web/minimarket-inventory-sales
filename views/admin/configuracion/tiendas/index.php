<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<?php 
    include_once __DIR__ . '/../../../templates/alertas.php';          
?>

<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link " href="/admin/configuracion/tiendas/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Tiendas..."
                data-table-search="tablaTiendas"
            />
        </div>
    </div>  

    <div class="table-body">
        <?php if(!empty($tiendas)) { ?>
            <table id="tablaTiendas"  class="table" data-table data-page-size="5">
                <thead class="table__thead">
                    <tr>               
                        <th scope='col' class="table__th">Código</th>  
                        <th scope='col' class="table__th">Nombre</th>   
                        <th scope='col' class="table__th">Dirección</th>   
                        <th scope='col' class="table__th">Estado</th>                  
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($tiendas as $tienda) { ?>
                        <tr class="table__tr <?= !$tienda->activo ? 'fila--inactiva' : '' ?>">
                            <td class="table__td">
                                <?php echo $tienda->codigo ;?>
                            </td>          
                            <td class="table__td">
                                <?php echo $tienda->nombre ;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $tienda->direccion ;?>
                            </td>     
                            
                            <td class="table__td">
                                <label class="switch">
                                    <input 
                                        type="checkbox"
                                        class="js-switch-ajax"
                                        data-id="<?= $tienda->id ?>"
                                        data-modelo="Tiendas"
                                        <?= $tienda->activo ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </td>                              

                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a class="boton boton--primary" href="/admin/configuracion/tiendas/editar?id=<?php echo $tienda->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>                                
                                    </a>

                                    <form id ="frEliminar<?php echo $tienda->id; ?>"  method="POST" action="/admin/configuracion/tiendas/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $tienda->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $tienda->id; ?>"
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
        <div class="table-pagination" data-table-pagination="tablaTiendas"></div>  
    </div>
    
</div>