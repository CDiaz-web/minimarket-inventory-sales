<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>


    <div class="table-wrapper">      

            <div class="table-header">
                <div class="table-actions">
                    <a class="boton boton--primary-link " href="/admin/configuracion/tipopago/crear">
                        <i class="fa-solid fa-circle-plus"></i>
                        Añadir
                    </a>
                </div>
                <div class="table-search">
                    <input
                        class="formulario__input"
                        type="text"
                        placeholder="Buscar Tipos de Pago..."
                        data-table-search="tablaPagos"
                    />
                </div>
            </div>  

        <div class="table-body">
            <?php if(!empty($tipagos)) { ?>
                <table id="tablaPagos"  class="table" data-table data-page-size="10">
                    <thead class="table thead">
                        <tr>               
                            <th scope='col' class="table__th">Codigo</th>    
                            <th scope='col' class="table__th">Descripcion</th>      
                            <th scope='col' class="table__th">Estado</th>                 
                            <th scope='col' class="table__th">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="table-tbody" id="tabla">
                        <?php foreach($tipagos as $tipago) { ?>
                            <tr class="table__tr <?= !$tipago->activo ? 'fila--inactiva' : '' ?>">
                                <td class="table__td">
                                    <?php echo $tipago->codigo ;?>
                                </td>    
                                <td class="table__td">
                                    <?php echo $tipago->nombre;?>
                                </td> 
                                
                            <td class="table__td">
                                <label class="switch">
                                    <input 
                                        type="checkbox"
                                        class="js-switch-ajax"
                                        data-id="<?= $tipago->id ?>"
                                        data-modelo="TipoPago"
                                        <?= $tipago->activo ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </td>                                             

                                <td class="table__col-actions" >

                                    <div class="table__acciones">
                                        <a class="boton boton--primary" href="/admin/configuracion/tipopago/editar?id=<?php echo $tipago->id ?>">
                                            <i class="fa-solid fa-user-pen"></i>                                
                                        </a>

                                        <form id ="frEliminar<?php echo $tipago->id; ?>"  method="POST" action="/admin/configuracion/tipopago/eliminar" class="table__formulario">
                                            <input type="hidden" name="id" value="<?php echo $tipago->id; ?>">
                                            <button
                                                class="boton boton--danger"
                                                type="button"
                                                data-action="deleteRecord"
                                                data-id="<?php echo $tipago->id; ?>"
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
            <div class="table-pagination" data-table-pagination="tablaPagos"></div>  
        </div>
        
    </div>
