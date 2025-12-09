

<div class="bloques__grid-ventas_tabs">

    <fieldset class="formulario__fieldset">
    <h2 class="dashboard__heading--izquierda"><?php echo $titulo; ?></h2>  
        <div class="">
            <label for="nombre" class="formulario__label">
                <i class="fa-solid fa-list"></i>
                Productos
            </label>
        </div>  
        
        <div class="formulario__campo">  
            <input
                type = "text"
                class = "formulario__input"
                id="buscarArticulo"            
                placeholder="Ingrese el código de Barras o el nombre del producto"                        
            /> 
        </div>
        <div class="formulario__campo-xl">
            <div class="div-izquierda">
                <label for="costo" class="formulario__label">Total Venta: <?php  echo " " . $simbolo_moneda . " ";?><span id="totalVenta">0.00</span> </label>
            </div> 
    
            <div class="div-derecha">
                
                <button class="dashboard__boton-agregar" id = "btngenerar">
                    <i class="fa-solid fa-building"></i> Generar O.V.
                </button> 
                <button class="dashboard__boton-naranja" id = "btnnuevo">
                    <i class="fa-solid fa-user-plus"></i> Cliente
                </button>
                <button class="dashboard__boton-limpiar" id="eliminarTodo">
                    <i class="fa-solid fa-trash"></i> Vaciar
                </button>

            </div>
        </div>
        <br>
        <br>
        <br>
        <div class="dashboard__contenedor" >
            
            <table class="table" id="tablaArticulos">
                <thead class="table__thead">
                    <tr>               
                        <!-- <th scope='col' class="table__th">Codigo</th> -->
                        <th scope='col' class="table__th">Producto</th>
                        <th scope='col' class="table__th">Cantidad</th>
                        <th scope='col' class="table__th">UM</th>
                        <th scope='col' class="table__th">Precio</th>
                        <th scope='col' class="table__th">Total</th>
                        <th scope='col' class="table__th"></th>
                    </tr>
                </thead>
                <tbody class="table__tbody" id="tabla">
                    
                </tbody>
            </table>
    
        </div>
        <div id="index_native_master" class="box"></div> 
    </fieldset>

   
</div>    

<script>
    // let tpagoDefecto = <?php echo json_encode($_SESSION['tpago_defecto']); ?>;
    const moneda_base = "<?= $moneda ?>";
    const forma_pago = "<?= $tpago_defecto ?>";
    const valida_tc = "<?= $validar_tc ?>";
    
</script>