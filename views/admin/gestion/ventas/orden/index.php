<?php
    $idMonedaSeleccionada = $cabecera->idmoneda ?? $moneda;
    $idFPagoSeleccionada = $cabecera->idtipopago ?? $tpago_defecto;    
?>
<h2 class="dashboard__heading--izquierda"><?php echo $titulo; ?></h2>  

<div class="table-wrapper">      

    <div class="table-gestion">       
        <div class="grupo-cliente-lista">
            <!-- clientes -->
            <div class="campo-inline campo-inline--cliente">
                <div>
                    <label for="nombre_cliente" class="formulario__label">
                        <i class="fas fa-users"></i>
                        Cliente
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
                        id="idcliente_hidden"
                        value="<?= $cabecera->idcliente ?? '' ?>"
                    >

                    <input
                        type = "text"
                        class = "formulario__input"
                        id="buscarCliente"            
                        placeholder="Ingrese Nombre del Cliente"     
                        value="<?= $cabecera->cliente ?? '' ?>"                     
                    /> 
                </div> 
            </div>
            <!-- lista de precios -->
            <div class="campo-inline campo-inline--lista">
                <div>
                    <label for="nombre_lista" class="formulario__label">
                        <i class="fa-solid fa-list"></i>
                        Lista de Precios
                    </label>
                </div>  
                <div class="formulario__campo">  

                    <input
                        type="hidden"
                        id="idlista_hidden"
                        value="<?= $cabecera->idlista ?? '' ?>"
                    >

                    <input
                        type = "text"
                        class = "formulario__input"
                        id="buscarLista"            
                        placeholder="Ingrese Lista de Precios"    
                        value="<?= $cabecera->lista ?? '' ?>"                                         
                    /> 
                </div>
            </div> 
        </div>


        <!-- boque de una sola fila -->
        <div class="grupo-fecha-moneda-pago">

            <!-- Fecha -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa fa-calendar"></i>
                    Fecha
                </label>
                <input 
                    type="date"
                    class="formulario__input"
                    id="fecha_venta"
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
                    id="tipoCambio_venta"
                    disabled                   
                />
            </div> 

            <!-- Forma de pago -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-credit-card-alt"></i>
                    Forma de Pago
                </label>
                <select class="formulario__select" id="idtipopago" name="idtipopago">
                    <option value="">-Seleccionar-</option>

                    <?php foreach ($lista_forma_pago as $forma_pago_lista) { ?>
                        <option
                            value="<?= $forma_pago_lista->id ?>"
                            data-requierecobro="<?= $forma_pago_lista->requiere_cobro ?>"
                            <?= ($idFPagoSeleccionada == $forma_pago_lista->id) ? 'selected' : '' ?>
                        >
                            <?= $forma_pago_lista->nombre ?>
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
                id="observacion_venta"
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
                id="buscarProducto"            
                placeholder="Ingrese el código de Barras o el nombre del producto"                        
            /> 
        </div>
    </div>  

    <div class="table-header">
        <div class="table-actions">
            <button class="boton boton--primary-link" id = "btngenerar">
                <i class="fa-solid fa-pen-to-square"></i> Guardar O.V.
            </button> 

            <button class="boton boton--danger-link" id="eliminarTodo">
                <i class="fa-solid fa-trash"></i> Limpiar
            </button>            
        </div>

        <div class="table-search">
            <label 
                for="costo" 
                class="formulario__label">
                Sub Total: 
                <span id="simboloMoneda01"><?php echo $cabecera->simbolo ?? $simbolo_moneda; ?></span>
                <span id="subtotalVenta"><?php echo $cabecera->subtotal_origen ?? "0.00"; ?></span> 
            </label>
        </div>
        <div class="table-search">
            <label 
                for="costo" 
                class="formulario__label">
                Igv: 
                <span id="simboloMoneda02"><?php echo $cabecera->simbolo ?? $simbolo_moneda; ?></span>
                <span id="totalVentaIgv"><?php echo $cabecera->igv_origen ?? "0.00"; ?></span> 
            </label>
        </div>

        <div class="table-search">
            <label 
                for="costo" 
                class="formulario__label">
                Total Venta: 
                <span id="simboloMoneda03"><?php echo $cabecera->simbolo ?? $simbolo_moneda; ?></span>
                <span id="totalVenta"><?php echo $cabecera->total_origen ?? "0.00"; ?></span> 
            </label>
        </div>
    </div>

    

    <div class="table-body">
        
            <table id="tablaArticulosVentas"  class="table" data-table data-page-size="20">
                <thead class="table__thead">
                    <tr>               
                        <th scope='col' class="table__th">Producto</th>
                        <th scope='col' class="table__th">Cantidad</th>
                        <th scope='col' class="table__th">UM</th>
                        <th scope='col' class="table__th">
                            <div >Precio</div>
                            <div style="font-size:11px;color:#6c757d;font-weight:400;line-height:12px;">incluye IGV</div>
                        </th>  
                        <th scope='col' class="table__th" hidden>Precio Sin Igv</th>
                        <th scope='col' class="table__th">
                            <div >Total</div>
                            <div style="font-size:11px;color:#6c757d;font-weight:400;line-height:12px;">incluye IGV</div>
                        </th>  
                        <th scope='col' class="table__th" hidden>Total sin Igv</th>
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                 <tbody class="table__tbody" id="tabla">
                    
                </tbody>
            </table>   
                 
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaArticulosVentas"></div>  
    </div>    
</div>

