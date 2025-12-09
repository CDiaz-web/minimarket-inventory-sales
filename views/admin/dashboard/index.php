<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>


<div class="bloques__grid">
    <div class="bloque">        
        <p class="bloque__texto--cantidad"><?php echo $cards->totalProductos;?></p>  
        <!-- <p class="bloque__texto--cantidad">250.00</p>  -->
        <i class="fa-solid fa-clipboard bloque__icono"></i>      
        <h3 class="bloque__heading">Total Productos</h3>  
        
        <button class="bloque__blink"  type="button" id = "total-producto"">More Info
            <i class="fa-solid fa-circle-info"></i>
        </button> 

    </div>

    <div class="bloque">        
        <p class="bloque__texto--cantidad"><?php echo $simbolo_moneda ." ". number_format($cards->totalCompras, 2, '.', ',') ;?></p>         
        <i class="fa-solid fa-money-bill bloque__icono"></i>
        <h3 class="bloque__heading">Total Compras</h3>
        <button class="bloque__blink"  type="button" id = "total-compra"">More Info
            <i class="fa-solid fa-circle-info"></i>
        </button> 
    </div>

    <div class="bloque">        
        <p class="bloque__texto--cantidad"><?php echo $simbolo_moneda ." ". number_format($cards->totalVentas, 2, '.', ',');?></p>         
        <i class="fa-solid fa-cart-shopping bloque__icono"></i>
        <h3 class="bloque__heading">Total Ventas</h3>
        <button class="bloque__blink"  type="button" id = "total-venta"">More Info
            <i class="fa-solid fa-circle-info"></i>
        </button> 
    </div>
    <div class="bloque">        
        <p class="bloque__texto--cantidad"><?php echo $simbolo_moneda ." ".  number_format($cards->totalGanancias, 2, '.', ',');?></p>        
        <i class="fa-solid fa-chart-pie bloque__icono"></i>
        <h3 class="bloque__heading">Total Ganacias</h3>
        <button class="bloque__blink"  type="button" id = "total-ganacia"">More Info
            <i class="fa-solid fa-circle-info"></i>
        </button> 
    </div>
    <div class="bloque">        
        <p class="bloque__texto--cantidad"><?php echo $cards->productosPocoStock;?></p>        
        <i class="fa-solid fa-triangle-exclamation bloque__icono"></i>
        <h3 class="bloque__heading">Poco stock</h3>
        <button class="bloque__blink"  type="button" id = "poco-stock"">More Info
            <i class="fa-solid fa-circle-info"></i>
        </button> 
    </div>
    <div class="bloque"> 
        <p class="bloque__texto--cantidad"><?php echo $simbolo_moneda ." ".  number_format($cards->ventasHoy, 2, '.', ',');?></p>        
        <i class="fa-solid fa-calendar-days bloque__icono"></i>         
        <h3 class="bloque__heading">Ventas del Dia</h3>
        <button class="bloque__blink"  type="button" id = "ventas-dia"">More Info
            <i class="fa-solid fa-circle-info"></i>
        </button> 
    </div>

</div>
<br>
<div class="dashboard__contenedor">
    <div class="chart">
        <canvas id="myChart" style="min-height: 250px; height: 300px; max-height:350px; width:100%;">

        </canvas>
    </div>
</div>
<br>
<div class="bloques__grid-tablas">
    <div class="dashboard__formulario_xl">
        <p class="bloques__grid-titulo">10 Producos Mas Vendidos</p>
        <div class="datagrid">
            <table class="tabla" id="tbl_productos_mas_vendidos">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>                       
                        <th><?php echo $simbolo_moneda . " ";?>Ventas</th>
                    </tr>
                </thead>
                <tbody class="table__tbody">

                </tbody>
            </table>            
        </div>      

  

    </div>
    <div class="dashboard__formulario_xl">
        <p class="bloques__grid-titulo">Productos con Poco Stock</p>
        <div class="datagrid">
            <table class="tabla" id="tbl_productos_poco_stock">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Minimo Stock</th>
                    </tr>
                </thead>
                <tbody class="table__tbody">
                    
                </tbody>
            </table>
        </div>

       
    </div>
</div>