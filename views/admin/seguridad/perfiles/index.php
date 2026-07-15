<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>


<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link " href="/admin/seguridad/perfiles/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Perfiles..."
                data-table-search="tablaPerfiles"
            />
        </div>
    </div>  


    <div class="table-body">
        <?php if(!empty($perfiles)) { ?>
            <table id="tablaPerfiles"  class="table" data-table data-page-size="5">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Nombre</th>   
                        <th scope='col' class="table__th">Estado</th>              
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($perfiles as $perfil) { ?>
                        <tr class="table__tr <?= !$perfil->activo ? 'fila--inactiva' : '' ?>">
                            <td class="table__td">
                                <?php echo $perfil->nombre ;?>
                            </td>      
                            
                            
                            <td class="table__td">
                                <label class="switch">
                                    <input 
                                        type="checkbox"
                                        class="js-switch-ajax"
                                        data-id="<?= $perfil->id ?>"
                                        data-modelo="Perfiles"
                                        <?= $perfil->activo ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </td>  



                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a class="boton boton--primary" href="/admin/seguridad/perfiles/editar?id=<?php echo $perfil->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>                                
                                    </a>

                                    <a class="boton boton--primary"  href="/admin/seguridad/perfiles/opciones?id=<?php echo $perfil->id ?>">
                                        <i class="fa-solid fa-list-check"></i>                               
                                    </a>

                                    <form id ="frEliminar<?php echo $perfil->id; ?>"  method="POST" action="/admin/seguridad/perfiles/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $perfil->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $perfil->id; ?>"
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
            <p class="text-center">No hay Perfiles</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaPerfiles"></div>  
    </div>
    
</div>


