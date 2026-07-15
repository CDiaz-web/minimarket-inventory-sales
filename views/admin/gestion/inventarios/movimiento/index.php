<?php
    $idTipoSeleccionada = $cabecera->idtipo ?? '';
    $idTiendaSeleccionada = $cabecera->idtienda ?? '';     
?>

<h2 class="dashboard__heading--izquierda"><?php echo $titulo; ?></h2>  

<div class="table-wrapper">      

    <div class="table-gestion">       
         <div class="grupo-fecha-moneda">

            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-tags"></i>
                    Tipo Movimiento
                </label>
                <select class="formulario__select" id="idtipo" name="idtipo">
                    <option value="">-Seleccionar-</option>

                    <?php foreach ($tipos_movimientos as $tipo) { ?>
                        <option
                            value="<?= $tipo->id ?>"
                            data-accion="<?= $tipo->accion ?>"
                            data-transferencia="<?= $tipo->es_transferencia ?>"
                            <?= ($idTipoSeleccionada == $tipo->id) ? 'selected' : '' ?>
                        >
                            <?= $tipo->nombre ?>
                        </option>   
                    <?php } ?>
                </select>
            </div>         

            <!-- Fecha -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa fa-calendar"></i>
                    Fecha
                </label>
                <input 
                    type="date"
                    class="formulario__input"
                    id="fecha_movimiento"
                    name="fecha"                    
                    value="<?= $cabecera->fecha ?? $fecha ?>"  
                    required
                >
            </div>

            <!-- tienda -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-shop"></i>
                    Tienda Destino
                </label>
                <select class="formulario__select" id="idtienda" name="idtienda" disabled>
                    <option value="">-Seleccionar-</option>

                    <?php foreach ($tiendas as $tienda) { ?>
                        <option
                            value="<?= $tienda->id ?>"                                                        
                            <?= ($idTiendaSeleccionada == $tienda->id) ? 'selected' : '' ?>
                        >
                            <?= $tienda->nombre ?>
                        </option>   
                    <?php } ?>
                </select>
            </div>
        
        </div>        


        <!-- Observacion -->
        <div>
            <label for="observacion" class="formulario__label">
                <i class="fa-solid a fa-book"></i>
                Observacion
            </label>
        </div>  
        <div class="formulario__campo">  
            <input
                type = "text"
                class = "formulario__input"
                id="observacion_movimiento"
                value="<?= $cabecera->observacion ?? '' ?>"  
            /> 
        </div>          


        <!-- productos -->
        <div>
            <label for="nombre" class="formulario__label">
                <i class="fa-solid fa-basket-shopping"></i>
                Productos
            </label>
        </div>  
        
        <div class="formulario__campo">  
            <input
                type = "text"
                class = "formulario__input"
                id="buscarProductoMov"            
                placeholder="Ingrese el código de Barras o el nombre del producto"                        
            /> 
        </div>
    </div>  

    <div class="table-header">
        <div class="table-actions">
            <button class="boton boton--primary-link" id = "btngenera_mov">
                <i class="fa-solid fa-pen-to-square"></i> Guardar Mov.
            </button> 

            <button class="boton boton--danger-link" id="eliminarTodo">
                <i class="fa-solid fa-trash"></i> Limpiar
            </button>            
        </div>

    </div>

    

    <div class="table-body">
        
            <table id="tablaArticulosMovimientos"  class="table" data-table data-page-size="20">
                <thead class="table__thead">
                    <tr>               
                        <th scope='col' class="table__th">Producto</th>                        
                        <th scope='col' class="table__th">Cantidad</th>
                        <th scope='col' class="table__th">Stock</th>
                        <th scope='col' class="table__th">UM</th>         
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                 <tbody class="table__tbody" id="tabla">
                    
                </tbody>
            </table>   
                 
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaArticulosMovimientos"></div>  
    </div>    
</div>

