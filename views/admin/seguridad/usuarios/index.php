<h2 class="dashboard__heading" ><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>

<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link " href="/admin/seguridad/usuarios/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Usuario..."
                data-table-search="tablaUsuarios"
            />
        </div>
    </div>  

    <div class="table-body">
        <?php if(!empty($usuarios)) { ?>
            <table id="tablaUsuarios"  class="table" data-table data-page-size="5">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Usuario</th>
                        <th scope='col' class="table__th">Perfil</th>            
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($usuarios as $usuario) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $usuario->usuario ;?>
                            </td>  
                            <td class="table__td">
                                <?php echo $usuario->perfil ;?>
                            </td>                                
                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a class="boton boton--primary" href="/admin/seguridad/usuarios/editar?id=<?php echo $usuario->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>                                
                                    </a>
                                    <a href="#" 
                                        class="boton boton--primary btn-tienda" 
                                        data-action="asignarTienda"
                                        data-id="<?= $usuario->id ?>">                            
                                
                                        <i class="fa-solid fa-store"></i>  
                                    </a>

                                    <form id ="frEliminar<?php echo $usuario->id; ?>"  method="POST" action="/admin/seguridad/usuarios/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $usuario->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $usuario->id; ?>"
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
            <p class="text-center">No hay Usuarios Registradas</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaUsuarios"></div>  
    </div>
    
</div>




