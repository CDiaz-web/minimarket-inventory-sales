<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>

<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link " href="/admin/configuracion/correlativos/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>
        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar Tiendas..."
                data-table-search="tablaCorrela"
            />
        </div>
    </div>  


    <div class="table-body">
        <?php if(!empty($correlativos)) { ?>
            <table id="tablaCorrela"  class="table" data-table data-page-size="5">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Id</th>  
                        <th scope='col' class="table__th">Tienda</th>   
                        <th scope='col' class="table__th">Tipo Documento</th>                  
                        <th scope='col' class="table__th">Ultimo Correlativo</th>                  
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tabla">
                    <?php foreach($correlativos as $correlativo) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $correlativo->id ;?>
                            </td>          
                            <td class="table__td">
                                <?php echo $correlativo->nombre;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $correlativo->tipo_documento ;?>
                            </td>  
                            <td class="table__td">
                                <?php echo $correlativo->ultimo_numero ;?>
                            </td>                                  
                            <td class="table__col-actions" >

                                <div class="table__acciones">

                                    <form id ="frEliminar<?php echo $correlativo->id; ?>"  method="POST" action="/admin/configuracion/correlativos/eliminar" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $tienda->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $correlativo->id; ?>"
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
        <div class="table-pagination" data-table-pagination="tablaCorrela"></div>  
    </div>
    
</div>

