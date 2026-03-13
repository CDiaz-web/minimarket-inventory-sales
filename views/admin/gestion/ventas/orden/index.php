
<h2 class="dashboard__heading--izquierda"><?php echo $titulo; ?></h2>  

<div class="table-wrapper">      

    <div class="table-hgestion">
        <!-- clientes -->
        <div>
            <label for="nombre_cliente" class="formulario__label">
                <i class="fas fa-users"></i>
                Cliente
            </label>
        </div>  
        <div class="formulario__campo">  
            <input
                type = "text"
                class = "formulario__input"
                id="buscarCliente"            
                placeholder="Ingrese Nombre del Cliente"                        
            /> 
        </div>

        <!-- lista de precios -->
        <div>
            <label for="nombre_lista" class="formulario__label">
                <i class="fa-solid fa-list"></i>
                Lista de Precios
            </label>
        </div>  
        <div class="formulario__campo">  

            <input
                type = "text"
                class = "formulario__input"
                id="buscarLista"            
                placeholder="Ingrese Lista de Precios"                        
            /> 
        </div>
        <!-- Moneda -->
        <div>
            <label for="nombre_lista" class="formulario__label">
                <i class="fa-solid fa-comment-dollar"></i>
                Moneda
            </label>
        </div>  
        <div class="formulario__campo">  
            <select class="formulario__select" id="idmoneda" name ="idmoneda">
            <option value="" >-Seleccionar-</option>
                <?php foreach($lista_monedas as $moneda_lista) { ?>
                    <option <?php echo ($moneda == $moneda_lista->id) ? 'selected' : '' ; ?> value="<?php echo $moneda_lista->id; ?>" > <?php echo $moneda_lista->nombre; ?> </option>
                <?php }?> 
            </select>
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
                <i class="fa-solid fa-building"></i> Generar O.V.
            </button> 
            <!-- <button class="boton boton--warning-link" id = "btnlimpiar">
                <i class="far fa-file"></i> Limpiar
            </button> -->
            <!-- <button class="boton boton--success-light" id = "btnnuevo">
                <i class="fa-solid fa-user-plus"></i> Cliente
            </button> -->
            <button class="boton boton--danger-link" id="eliminarTodo">
                <i class="fa-solid fa-trash"></i> Limpiar
            </button>            
        </div>
        <div class="table-search">
            <label 
                for="costo" 
                class="formulario__label">
                Total Venta: 
                <span id="simboloMoneda"><?php echo $simbolo_moneda; ?></span>
                <span id="totalVenta">0.00</span> 
            </label>
        </div>
    </div>

    

    <div class="table-body">
        
            <table id="tablaArticulos"  class="table" data-table data-page-size="20">
                <thead class="table__thead">
                    <tr>               
                        <th scope='col' class="table__th">Producto</th>
                        <th scope='col' class="table__th">Cantidad</th>
                        <th scope='col' class="table__th">UM</th>
                        <th scope='col' class="table__th">Precio</th>
                        <th scope='col' class="table__th">Total</th>
                        <th scope='col' class="table__th">Acciones</th>
                    </tr>
                </thead>
                 <tbody class="table__tbody" id="tabla">
                    
                </tbody>
            </table>   
                 
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaArticulos"></div>  
    </div>    
</div>

<script>
    // let tpagoDefecto = <?php echo json_encode($_SESSION['tpago_defecto']); ?>;
    const moneda_base = "<?= $moneda ?>";
    const forma_pago = "<?= $tpago_defecto ?>";
    const valida_tc = "<?= $validar_tc ?>";
    
</script>