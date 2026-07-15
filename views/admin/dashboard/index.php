<?php

function generarIndicador($actual, $anterior, $comparacion = 'vs anterior') {

    if ($anterior == 0) {
        return [
            'clase' => 'positivo',
            'icono' => 'fa-arrow-up',
            'texto' => '+100% ' . $comparacion
        ];
    }

    $variacion = (($actual - $anterior) / $anterior) * 100;

    if ($variacion > 0) {
        return [
            'clase' => 'positivo',
            'icono' => 'fa-arrow-up',
            'texto' => '+' . round($variacion, 1) . '% ' . $comparacion
        ];
    } elseif ($variacion < 0) {
        return [
            'clase' => 'negativo',
            'icono' => 'fa-arrow-down',
            'texto' => round($variacion, 1) . '% ' . $comparacion
        ];
    } else {
        return [
            'clase' => 'neutro',
            'icono' => 'fa-minus',
            'texto' => '0% sin cambios'
        ];
    }
}

$indicadorDia = generarIndicador($cards->ventasHoy, $cards->ventasAyer, 'vs ayer');

$indicadorMes = generarIndicador($cards->ventasMes, $cards->ventasMesAnterior, 'vs mes anterior');

$indicadorGanancia = generarIndicador($cards->totalGanancias, $cards->totalGananciasAnt, 'vs mes anterior');

$indicadorCompras = generarIndicador($cards->totalCompras, $cards->totalComprasAnt, 'vs mes anterior');

?>

<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>


<div class="dashboard__grid">

    <div class="ds-card ds-card--hover ds-card--morado" id="card-ventas-dia"
    
        data-modal="card"
        data-title="Detalle de Ventas del Día"
        data-endpoint="/api/listaventasdeldia"
        data-columns='[
            {"field":"codigo","label":"Código"},
            {"field":"nombre","label":"Producto"},
            {"field":"cantidad","label":"Cantidad"},
            {"field":"precio","label":"Precio"},
            {"field":"total","label":"Total"}
        ]'         
    >
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
            <span class="dashboard-card__extra <?php echo $indicadorDia['clase']; ?>">
                <i class="fa-solid <?php echo $indicadorDia['icono']; ?>"></i>
                <?php echo $indicadorDia['texto']; ?>
            </span>
        </div>
    </div>


    <div class="ds-card ds-card--hover ds-card--warning" id="card-total-ventas"
    
        data-modal="card"
        data-title="Ventas del Mes"
        data-endpoint="/api/listaventasdelmes"
        data-columns='[
            {"field":"codigo","label":"Código"},
            {"field":"nombre","label":"Producto"},
            {"field":"cantidad","label":"Cantidad"},
            {"field":"precio","label":"P.Venta"},
            {"field":"total","label":"Total"}
        ]'           
    
    >

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
                <?php echo $simbolo_moneda ." ". number_format($cards->ventasMes, 2, '.', ',');?>
            </span>
            <span class="dashboard-card__extra <?php echo $indicadorMes['clase']; ?>">
                <i class="fa-solid <?php echo $indicadorMes['icono']; ?>"></i>
                <?php echo $indicadorMes['texto']; ?>
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
            <span class="dashboard-card__extra <?php echo $indicadorGanancia['clase']; ?>">
                <i class="fa-solid <?php echo $indicadorGanancia['icono']; ?>"></i>
                <?php echo $indicadorGanancia['texto']; ?>
            </span>

        </div>

    </div>  


    <div class="ds-card ds-card--hover ds-card--primary" id="card-total-productos"
    
        data-modal="card"
        data-title="Total Productos"
        data-endpoint="/api/listaproductostienda"
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

    <div class="ds-card ds-card--hover ds-card--amarillo" id="card-poco-stock"
    
        data-modal="card"
        data-title="Productos con Poco Stock"
        data-endpoint="/api/listapocostock"
        data-columns='[
            {"field":"codigo","label":"Código"},
            {"field":"nombre","label":"Producto"},
            {"field":"stock","label":"Stock"},
            {"field":"stock_min","label":"Stock Min"}
        ]'   
    >

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


    <div class="ds-card card--hover ds-card--success" id="card-total-compras"
    
        data-modal="card"
        data-title="Compras del Mes"
        data-endpoint="/api/listacomprasmes"
        data-columns='[
            {"field":"codigo","label":"Código"},
            {"field":"nombre","label":"Producto"},
            {"field":"cantidad","label":"Cantidad"},
            {"field":"costo","label":"Costo"},
            {"field":"total","label":"Total"}
        ]'       
    
    
    >

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
            <span class="dashboard-card__extra <?php echo $indicadorCompras['clase']; ?>">
                <i class="fa-solid <?php echo $indicadorCompras['icono']; ?>"></i>
                <?php echo $indicadorCompras['texto']; ?>
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
            <div class="datagrid">
                <?php if(!empty($productostop)) { ?>
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
                     <?php foreach($productostop as $productotop) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $productotop->codigo ;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $productotop->nombre;?>
                            </td>   
                            <td class="table__td">
                                <?php echo $productotop->cantidad;?>
                            </td>
                            <td class="table__td">                                
                                <?php echo number_format($productotop->venta, 2, '.', ''); ?>
                            </td>                            
                        </tr>
                    <?php } ?>   
                    </tbody>
                </table>    
                <?php } else { ?>
                    <p class="text-center">No hay Información para Mostrar</p>
                <?php } ?>
            </div>         
        </div>   
    </div>

    <div class="ds-card ds-card--hover">
        <div class="ds-card__header">
            Productos sin movimiento en los últimos 60 días
        </div>

        <div class="ds-card__body">
        <!-- <p class="bloques__grid-titulo">Productos con Poco Stock</p> -->
            <div class="datagrid">
                <?php if(!empty($productosdown)) { ?>
                <table class="tabla" id="tbl_productos_poco_stock">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Producto</th>
                            <th>Ultima Venta</th>
                            <th>Días sin Venta</th>
                        </tr>
                    </thead>
                    <tbody class="table__tbody">
                     <?php foreach($productosdown as $productodown) { ?>
                        <tr class="table__tr">
                            <td class="table__td">
                                <?php echo $productodown->codigo ;?>
                            </td>    
                            <td class="table__td">
                                <?php echo $productodown->nombre;?>
                            </td>   
                            <td class="table__td">
                                <?php echo $productodown->fechacrea;?>
                            </td>
                            <td class="table__td">
                                <?php echo $productodown->cantidad;?>
                            </td>                            
                        </tr>
                    <?php } ?>                       
                    </tbody>
                </table>
                <?php } else { ?>
                    <p class="text-center">No hay Información para Mostrar</p>
                <?php } ?>
            </div>
        </div>       
    </div>

</div>

<div class="modal fade" id="cardModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content table-wrapper">
            <!-- HEADER -->           

            <div class="modal-header ds-card--success">

                <div class="modal-header__top">

                    <div class="dashboard-card__icono">
                        <i class="fa-solid fa-list"></i>
                    </div>

                    <h5 id="cardModalTitle" class="modal-title"></h5>

                    <button 
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-header__bottom">
                    <input 
                        class="formulario__input modal-search"
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

