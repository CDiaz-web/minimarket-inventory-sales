<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>


    <div class="table-wrapper">      

            <div class="table-header">
                <div class="table-actions">
                    <a class="boton boton--primary-link " href="/admin/configuracion/series/crear">
                        <i class="fa-solid fa-circle-plus"></i>
                        Añadir
                    </a>
                </div>
                <div class="table-search">
                    <input
                        class="formulario__input"
                        type="text"
                        placeholder="Buscar Series..."
                        data-table-search="tablaSeries"
                    />
                </div>
            </div>  

        <div class="table-body">
            <?php if(!empty($series)) { ?>
                <table id="tablaSeries"  class="table" data-table data-page-size="10">
                    <thead class="table thead">
                        <tr>               
                            <th scope='col' class="table__th">Tienda</th>    
                            <th scope='col' class="table__th">Doc.</th>      
                            <th scope='col' class="table__th">Serie</th>      
                            <th scope='col' class="table__th">Ult. Corr.</th>      
                            <th scope='col' class="table__th">Estado</th>                 
                            <th scope='col' class="table__th">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-tbody" id="tabla">
                        <?php foreach($series as $serie) { ?>
                            <tr class="table__tr <?= !$serie->activo ? 'fila--inactiva' : '' ?>">
                                <td class="table__td">
                                    <?php echo $serie->nombre;?>
                                </td> 
                                <td class="table__td">
                                    <?php echo $serie->codigo ;?>
                                </td>    
                             <td class="table__td">
                                    <?php echo $serie->serie ;?>
                                </td>   
                                <td class="table__td">
                                    <?php echo $serie->ultimo_correlativo ;?>
                                </td>    
                                
                            <td class="table__td">
                                <label class="switch">
                                    <input 
                                        type="checkbox"
                                        class="js-switch-ajax"
                                        data-id="<?= $serie->id ?>"
                                        data-modelo="Series"
                                        <?= $serie->activo ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </td>                                             

                                <td class="table__col-actions" >

                                    <div class="table__acciones">
                                        <a class="boton boton--primary" href="/admin/configuracion/series/editar?id=<?php echo $serie->id ?>">
                                            <i class="fa-solid fa-user-pen"></i>                                
                                        </a>

                                        <form id ="frEliminar<?php echo $serie->id; ?>"  method="POST" action="/admin/configuracion/series/eliminar" class="table__formulario">
                                            <input type="hidden" name="id" value="<?php echo $serie->id; ?>">
                                            <button
                                                class="boton boton--danger"
                                                type="button"
                                                data-action="deleteRecord"
                                                data-id="<?php echo $serie->id; ?>"
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
                <p class="text-center">No hay Series Registradas</p>
            <?php } ?>
        </div>    

        <div class="table-footer">
            <div class="table-pagination" data-table-pagination="tablaSeries"></div>  
        </div>
        
    </div>
