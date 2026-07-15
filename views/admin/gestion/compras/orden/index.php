<?php
    $idMonedaSeleccionada = $cabecera->idmoneda ?? $moneda;    
?>
<h2 class="dashboard__heading--izquierda"><?php echo $titulo; ?></h2> 

<div class="table-wrapper">      

    <div class="table-gestion">
        <!-- Proveedor -->
        <div>
            <label for="nombre_proveedor" class="formulario__label">
                <i class="fas fa-users"></i>
                Proveedores
            </label>
        </div>  
        <div class="formulario__campo">  

            <input
                type="hidden"
                id="idorden"
                value="<?= $cabecera->idorden ?? '' ?>"
            >

            <input
                type="hidden"
                id="idproveedor_hidden"
                value="<?= $cabecera->idproveedor ?? '' ?>"
            >
            <input
                type = "text"
                class = "formulario__input"
                id="buscarProveedor"            
                placeholder="Ingrese Nombre del Proveedor"          
                value="<?= $cabecera->proveedor ?? '' ?>"              
            /> 
        </div>

        <!-- boque de una sola fila -->
        <div class="grupo-fecha-moneda">

            <!-- Fecha -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa fa-calendar"></i>
                    Fecha
                </label>
                <input 
                    type="date"
                    class="formulario__input"
                    id="fecha_compra"
                    name="fecha"                    
                    value="<?= $cabecera->fecha ?? $fecha ?>"  
                    required
                >
            </div>

            <!-- Moneda -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-coins"></i>
                    Moneda
                </label>
                <select class="formulario__select" id="idmoneda" name="idmoneda">
                    <option value="">-Seleccionar-</option>

                    <?php foreach ($lista_monedas as $moneda_lista) { ?>
                        <option
                            value="<?= $moneda_lista->id ?>"
                            <?= ($idMonedaSeleccionada == $moneda_lista->id) ? 'selected' : '' ?>
                        >
                            <?= $moneda_lista->nombre ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Tipo de Cambio -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                    Tipo de Cambio
                </label>
                <input 
                    type="text"
                    class="formulario__input"
                    id="tipoCambio"
                    disabled                   
                />
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
                id="observacion_compra"
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
                id="buscarProductoCompra"            
                placeholder="Ingrese el código de Barras o el nombre del producto"                        
            /> 
        </div>
    </div>  

    <div class="table-header">
        <div class="table-actions">
            <button class="boton boton--primary-link" id = "btngenerarOC">
                <i class="fa-solid fa-pen-to-square"></i> Guardar O.C.
            </button> 

            <button class="boton boton--danger-link" id="LimpiarOC">
                <i class="fa-solid fa-trash"></i> Limpiar
            </button>            
        </div>
        <div class="table-search">
            <label 
                for="costo" 
                class="formulario__label">
                Sub Total: 
                <span id="simboloMoneda01"><?php echo $cabecera->simbolo ?? $simbolo_moneda; ?></span>
                <span id="subtotalCompra"><?php echo $cabecera->subtotal_origen ?? "0.00"; ?></span> 
            </label>
        </div>
        <div class="table-search">
            <label 
                for="costo" 
                class="formulario__label">
                Igv: 
                <span id="simboloMoneda02"><?php echo $cabecera->simbolo ?? $simbolo_moneda; ?></span>
                <span id="totalIgv"><?php echo $cabecera->igv_origen ?? "0.00"; ?></span> 
            </label>
        </div>
        <div class="table-search">
            <label 
                for="costo" 
                class="formulario__label">
                Total Compra: 
                <span id="simboloMoneda03"><?php echo $cabecera->simbolo ?? $simbolo_moneda; ?></span>
                <span id="totalCompra"><?php echo $cabecera->total_origen ?? "0.00"; ?></span> 
            </label>
        </div>
    </div>    

    <div class="table-body">
        
            <table id="tablaArticulosCompras"  class="table" data-table data-page-size="20">
                <thead class="table__thead">
                    <tr>               
                        <th scope='col' class="table__th">Producto</th>
                        <th scope='col' class="table__th">Cantidad</th>
                        <th scope='col' class="table__th">UM</th>
                        <th scope='col' class="table__th">
                            <div >Costo Compra</div>
                            <div style="font-size:11px;color:#6c757d;font-weight:400;line-height:12px;">incluye IGV</div>
                        </th>  
                        <th scope='col' class="table__th" hidden>Costo Base </th>                                              
                        <th scope='col' class="table__th" >
                            <div >Total Compra</div>
                            <div style="font-size:11px;color:#6c757d;font-weight:400;line-height:12px;">incluye IGV</div>
                        </th>
                        <th scope='col' class="table__th" hidden>Total base
                        </th>
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                 <tbody class="table__tbody" id="tabla">
                    
                </tbody>
            </table>   
                 
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaArticulosCompras"></div>  
    </div>    
</div>

