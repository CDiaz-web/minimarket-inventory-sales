<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../../templates/alertas.php';          
    ?>

<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link" href="/admin/mantenimiento/clientes/clientes/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
            
            <div class="table-actions">
                <button class="boton boton--primary"                  
                    data-action="exportTable"
                    data-table="tablaClientes"
                    data-file="clientes.xlsx"
                    data-sheet="Clientes"
                >
                    Exportar
                </button>
            </div>

            <a class="boton boton--primary-link"  href="/admin/mantenimiento/clientes/clientes/cargar">
                <i class="fa-solid fa-circle-down"></i>
                Carga Masiva
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Clientes..."
                data-table-search="tablaClientes"
            />
        </div>
    </div>  


    <div class="table-body">
        <?php if(!empty($clientes)) { ?>
            <table id="tablaClientes"  class="table" data-table data-page-size="10">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Documento</th>        
                        <th scope='col' class="table__th">Nombre</th> 
                        <th scope='col' class="table__th">Clasificacion</th>     
                        <th scope='col' class="table__th">Estado</th>              
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($clientes as $cliente) { ?>
                        <tr class="table__tr <?= !$cliente->activo ? 'fila--inactiva' : '' ?>">
                            <td class="table__td">
                                <?php echo $cliente->documento ;?>
                            </td>              
                            <td class="table__td">
                                <?php echo $cliente->nombre_cliente ;?>
                            </td>  
                            <td class="table__td">
                                <?php echo $cliente->clasificacion ;?>
                            </td>      
                            <td class="table__td">
                                <label class="switch">
                                    <input 
                                        type="checkbox"
                                        class="js-switch-ajax"
                                        data-id="<?= $cliente->id ?>"
                                        data-modelo="Clientes"
                                        <?= $cliente->activo ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </td>                                     
                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a class="boton boton--primary" href="/admin/mantenimiento/clientes/clientes/editar?id=<?php echo $cliente->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>                                
                                    </a>

                                    <form id ="frEliminar<?php echo $cliente->id; ?>"  method="POST" action="/admin/mantenimiento/clientes/clientes/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $cliente->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $cliente->id; ?>"
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
            <p class="text-center">No hay Clientes Registradas</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaClientes"></div>  
    </div>
    
</div>


