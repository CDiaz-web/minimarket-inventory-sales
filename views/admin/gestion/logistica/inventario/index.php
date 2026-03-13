
<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../templates/alertas.php';          
    ?>


<div class="table-wrapper">      

    <div class="table-header">
        <div class="table-actions">
            <a class="boton boton--primary-link " href="/admin/gestion/logistica/inventario/crear">
                <i class="fa-solid fa-circle-plus"></i>
                Añadir
            </a>

            <form method="GET" class="dashboard__filtros">        
                <label for="anio" class="sr-only">Año</label>
                <input 
                    type="number" 
                    id="anio" 
                    name="anio" 
                    value="<?php echo $anio; ?>" 
                    min="2000" 
                    max="<?php echo date('Y')+1; ?>" 
                />

                <label for="mes">Mes:</label>
                <select id="mes" name="mes">
                
                    <?php 
                        $meses = [
                            1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril",
                            5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto",
                            9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
                        ];
                        foreach($meses as $num => $nombre): 
                    ?>
                        <option value="<?php echo $num; ?>" <?php echo ($mes == $num) ? 'selected' : ''; ?>>
                            <?php echo $nombre; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="boton boton--primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                </button>
            </form>

        </div>
        <div class="table-search">
            <input
                class="formulario__input"
                type="text"
                placeholder="Buscar..."
                data-table-search="tablaInventario"
            />
        </div>
    </div>     


    <div class="table-body">
        <?php if(!empty($inventarios)) { ?>
            <table id="tablaInventario"  class="table" data-table data-page-size="10">
                <thead class="table thead">
                    <tr>               
                        <th scope='col' class="table__th">Documento</th>    
                        <th scope='col' class="table__th">Fecha</th>    
                        <th scope='col' class="table__th">Tienda</th> 
                        <th scope='col' class="table__th">Movimiento</th>  
                        <th scope='col' class="table__th">Tda. Destino</th> 
                        <th scope='col' class="table__th">Estado</th>                       
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                <tbody class="table-tbody" id="tablaInventario">
                    <?php foreach($inventarios as $inventario) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $inventario->documento;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $inventario->fecha;?>
                            </td>
                            <td class="table__td">
                                <?php echo $inventario->tienda_origen;?>
                            </td>  
                            <td class="table__td">
                                <?php echo $inventario->movimiento;?>
                            </td>
                            <td class="table__td">
                                <?php echo $inventario->tienda_destino;?>
                            </td>     
                            <td class="table__td">
                                <?php echo $inventario->estado;?>
                            </td>                                   
                            <td class="table__col-actions" >

                                <div class="table__acciones">
                                    <a 
                                        class="boton boton--primary"
                                        href="/admin/gestion/logistica/inventario/editar?id=<?php echo $inventario->id ?>">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </a>

                                    <button 
                                        class="boton boton--primary"  
                                        data-action="imprimirMovimiento"
                                        data-id="<?php echo $inventario->id ?>">
                                        <i class="fa fa-print"></i> 
                                    </button>      

                                    <form id ="frEliminar<?php echo $inventario->id; ?>"  method="POST" action="/admin/gestion/logistica/inventario/anular" class="table__formulario">
                                        <input type="hidden" name="id" value="<?php echo $inventario->id; ?>">
                                        <button
                                            class="boton boton--danger"
                                            type="button"
                                            data-action="deleteRecord"
                                            data-id="<?php echo $inventario->id; ?>"
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
            <p class="text-center">No hay Información Registrada</p>
        <?php } ?>
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaInventario"></div>  
    </div>
    
</div>



