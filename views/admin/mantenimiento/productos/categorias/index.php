<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../../templates/alertas.php';          
    ?>

<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link " href="/admin/mantenimiento/productos/categorias/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
            <button class="boton boton--primary"                  
                data-action="exportTable"
                data-table="tablaCategorias"
                data-file="categoria.xlsx"
                data-sheet="Categoria"
            >
                Exportar
            </button>
            <a class="boton boton--primary-link"  href="/admin/mantenimiento/productos/categorias/cargar">
                <i class="fa-solid fa-circle-down"></i>
                Carga Masiva
            </a>
        </div>

        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Categorias..."
                data-table-search="tablaCategorias"
            />
        </div>
    </div>  





    <div class="table-body">
        <?php if(!empty($categorias)) { ?>
            <table id="tablaCategorias"  class="table" data-table data-page-size="10">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Código</th>        
                        <th scope='col' class="table__th">Nombre</th>  
                        <th scope='col' class="table__th">Estado</th>                     
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($categorias as $categoria) { ?>
                        <tr class="table__tr <?= !$categoria->activo ? 'fila--inactiva' : '' ?>">
                            <td class="table__td">
                                <?php echo $categoria->codigo ;?>
                            </td>              
                            <td class="table__td">
                                <?php echo $categoria->nombre ;?>
                            </td>    
                            
                            <td class="table__td">
                                <label class="switch">
                                    <input 
                                        type="checkbox"
                                        class="js-switch-ajax"
                                        data-id="<?= $categoria->id ?>"
                                        data-modelo="Categorias"
                                        <?= $categoria->activo ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </td>                               
                            
                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a class="boton boton--primary" href="/admin/mantenimiento/productos/categorias/editar?id=<?php echo $categoria->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>                                
                                    </a>

                                    <form id ="frEliminar<?php echo $categoria->id; ?>"  method="POST" action="/admin/mantenimiento/productos/categorias/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $categoria->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $categoria->id; ?>"
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
            <p class="text-center">No hay Categorias Registradas</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaCategorias"></div>  
    </div>
    
</div>








