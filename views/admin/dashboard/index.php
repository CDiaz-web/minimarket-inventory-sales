

<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>


<div class="dashboard__grid">

    <div class="ds-card ds-card--hover ds-card--morado" id="card-ventas-dia">

        <div class="ds-card__body dashboard-card">

            <div class="dashboard-card__top">
                <div class="dashboard-card__icono">
                    <i class="fa-solid fa-coins"></i>
                </div>

                <span class="dashboard-card__titulo">
                    Ventas del Día
                </span>
            </div>

            <span class="dashboard-card__valor">
                <?php echo $simbolo_moneda ." ".  number_format($cards->ventasHoy, 2, '.', ',');?>
            </span>

        </div>

    </div>


    <div class="ds-card ds-card--hover ds-card--warning" id="card-total-ventas">

        <div class="ds-card__body dashboard-card">

            <div class="dashboard-card__top">
                <div class="dashboard-card__icono">
                    <i class="fa-solid fa-chart-column"></i>
                </div>

                <span class="dashboard-card__titulo">
                    Total Ventas
                </span>
            </div>

            <span class="dashboard-card__valor">
                <?php echo $simbolo_moneda ." ". number_format($cards->totalVentas, 2, '.', ',');?>
            </span>

        </div>

    </div>    


    <div class="ds-card ds-card--hover ds-card--danger" id="card-total-ganancias">

        <div class="ds-card__body dashboard-card">

            <div class="dashboard-card__top">
                <div class="dashboard-card__icono">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <span class="dashboard-card__titulo">
                    Total Ganacias
                </span>
            </div>

            <span class="dashboard-card__valor">
                <?php echo $simbolo_moneda ." ".  number_format($cards->totalGanancias, 2, '.', ',');?>
            </span>

        </div>

    </div>  


    <div class="ds-card ds-card--hover ds-card--primary" id="card-total-productos"
    
        data-modal="card"
        data-title="Total Productos"
        data-endpoint="/api/totalproductostienda"
        data-columns='[
            {"field":"codigo","label":"Código"},
            {"field":"nombre","label":"Producto"},
            {"field":"venta","label":"Precio Venta"},
            {"field":"stock","label":"Stock"},
            {"field":"stock_minimo","label":"Stock Min."},
            {"field":"stock_maximo","label":"Stock Max."}
        ]'     
    
    >

        <div class="ds-card__body dashboard-card">

            <div class="dashboard-card__top">
                <div class="dashboard-card__icono">
                    <i class="fa-solid fa-box"></i>
                </div>

                <span class="dashboard-card__titulo">
                    Total Productos
                </span>
            </div>

            <span class="dashboard-card__valor">
                <?php echo $cards->totalProductos;?>
            </span>

        </div>

    </div>  
    
    


    <div class="ds-card ds-card--hover ds-card--amarillo" id="card-poco-stock">

        <div class="ds-card__body dashboard-card">

            <div class="dashboard-card__top">
                <div class="dashboard-card__icono">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>

                <span class="dashboard-card__titulo">
                    Poco stock
                </span>
            </div>

            <span class="dashboard-card__valor">
                <?php echo $cards->productosPocoStock;?>
            </span>

        </div>

    </div>  


    <div class="ds-card card--hover ds-card--success" id="card-total-compras">

        <div class="ds-card__body dashboard-card">

            <div class="dashboard-card__top">
                <div class="dashboard-card__icono">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>

                <span class="dashboard-card__titulo">
                    Compras del Mes
                </span>
            </div>

            <span class="dashboard-card__valor">
                <?php echo $simbolo_moneda ." ". number_format($cards->totalCompras, 2, '.', ',') ;?>
            </span>

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

