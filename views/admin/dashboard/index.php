

<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>


<div class="dashboard__grid">


    <div class="ds-card ds-card--hover ds-card--primary">
        <div class="ds-card__body dashboard-card">

            <span class="dashboard-card__titulo">
                Total Productos
            </span>

            <span class="dashboard-card__valor">
                <?php echo $cards->totalProductos;?>
            </span>

            <button 
                class="bloque__blink" 
                type="button" 
                data-modal="card"
                data-title="Total Productos"
                data-endpoint="/api/totalproductostienda"
                data-columns=
                '[
                    {"field":"codigo","label":"Código"},
                    {"field":"nombre","label":"Producto"},
                    {"field":"venta","label":"Precio Venta"},
                    {"field":"stock","label":"Stock"},
                    {"field":"stock_minimo","label":"Stock Min."},
                    {"field":"stock_maximo","label":"Stock Max."}
                ]'
            >
                More Info
                <i class="fa-solid fa-circle-info"></i>
            </button>

        </div>
    </div>

    <div class="ds-card card--hover ds-card--success">
        <div class="ds-card__body dashboard-card">

            <span class="dashboard-card__titulo">
                Total Compras
            </span>

            <span class="dashboard-card__valor">
                <?php echo $simbolo_moneda ." ". number_format($cards->totalCompras, 2, '.', ',') ;?>
            </span>

            <button class="bloque__blink" type="button" id="total-compra">
                More Info
                <i class="fa-solid fa-circle-info"></i>
            </button>

        </div>
    </div>


    <div class="ds-card ds-card--hover ds-card--warning">
        <div class="ds-card__body dashboard-card">

            <span class="dashboard-card__titulo">
                Total Ventas
            </span>

            <span class="dashboard-card__valor">
                <?php echo $simbolo_moneda ." ". number_format($cards->totalVentas, 2, '.', ',');?>
            </span>

            <button class="bloque__blink" type="button" id="total-venta">
                More Info
                <i class="fa-solid fa-circle-info"></i>
            </button>

        </div>
    </div>


    <div class="ds-card ds-card--hover ds-card--danger">
        <div class="ds-card__body dashboard-card">

            <span class="dashboard-card__titulo">
                Total Ganacias
            </span>

            <span class="dashboard-card__valor">
                <?php echo $simbolo_moneda ." ".  number_format($cards->totalGanancias, 2, '.', ',');?>
            </span>

            <button class="bloque__blink" type="button" id="total-ganacia">
                More Info
                <i class="fa-solid fa-circle-info"></i>
            </button>

        </div>
    </div>



    <div class="ds-card ds-card--hover ds-card--morado">
        <div class="ds-card__body dashboard-card">

            <span class="dashboard-card__titulo">
                Poco stock
            </span>

            <span class="dashboard-card__valor">
                <?php echo $cards->productosPocoStock;?>
            </span>

            <button class="bloque__blink" type="button" id="poco-stock">
                More Info
                <i class="fa-solid fa-circle-info"></i>
            </button>

        </div>
    </div>



    <div class="ds-card ds-card--hover ds-card--amarillo">
        <div class="ds-card__body dashboard-card">

            <span class="dashboard-card__titulo">
                Ventas del Dia
            </span>

            <span class="dashboard-card__valor">
                <?php echo $simbolo_moneda ." ".  number_format($cards->ventasHoy, 2, '.', ',');?>                 
            </span>

            <button class="bloque__blink" type="button" id="ventas-dia">
                More Info
                <i class="fa-solid fa-circle-info"></i>
            </button>

        </div>
    </div>

</div>
<br>

<div class="dashboard__paneles">

    <div class="ds-card ds-card--hover">
        <div class="ds-card__header">
            Ventas Mensuales
        </div>

        <div class="ds-card__body">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>
<br>
<div class="dashboard__tablas">

    <div class="ds-card ds-card--hover">
        <div class="ds-card__header">
            10 Productos Más Vendidos
        </div>
        <div class="ds-card__body">
            <!-- <p class="bloques__grid-titulo">10 Producos Mas Vendidos</p> -->
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
    </div>

    <div class="ds-card ds-card--hover">
        <div class="ds-card__header">
            Productos con Poco Stock
        </div>

        <div class="ds-card__body">
        <!-- <p class="bloques__grid-titulo">Productos con Poco Stock</p> -->
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

</div>

<div class="modal fade" id="cardModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content table-wrapper">
            <!-- HEADER -->
            <div class="modal-header table-header">
                <h5 id="cardModalTitle"></h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
                <div class="table-search">
                    <input 
                        class="formulario__input"
                        type="text"
                        placeholder="Buscar..."
                        data-table-search="cardModalTable"
                    />
                </div>
            </div>
            
  
            <div class="modal-body table-body">

                <table id="cardModalTable" class="table" data-table>
                    <thead></thead>
                    <tbody class="table-tbody"></tbody>
                </table>

            </div>
            <!-- FOOTER -->
            <div class="table-footer">
                <div 
                    class="table-pagination"
                    data-table-pagination="cardModalTable">
                </div>
            </div>

        </div>
    </div>
</div>

