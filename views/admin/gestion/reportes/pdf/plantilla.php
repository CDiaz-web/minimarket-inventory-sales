<style>
#header {
    position: fixed;
    top: -120px;
    left: 0;
    right: 0;
    height: 120px;
}
#footer {
    position: fixed;
    bottom: -50px;
    left: 0;
    right: 0;
    height: 50px;
    text-align: center;
    font-size: 10px;
}


@page {
    margin-top: 140px;
    margin-bottom: 70px;
    margin-left: 40px;
    margin-right: 40px;
}

body {
    font-family: Arial, sans-serif;
    font-size: 12px;
}

.header {
    width: 100%;
    margin-bottom: 15px;
}

.header-table {
    width: 100%;
}

.logo {
    width: 120px;
    height: auto;
}

.titulo {
    font-size: 18px;
    font-weight: bold;
}

.subinfo {
    font-size: 11px;
    color: #444;
}

hr {
    border: 0;
    border-top: 1px solid #000;
    margin-top: 10px;
}
</style>

<div id="header">

    <table width="100%">
        <tr>
            <td width="25%">
                <?php if($logo): ?>
                    <img src="<?= $logo ?>" class="logo">
                <?php endif; ?>
            </td>

            <td width="75%">
                <strong><?= $empresa ?></strong><br>
                <?= $tienda ?><br><br>

                <strong><?= $titulo ?></strong><br>
                <?= $rango_fechas ?><br>
                Fecha impresión: <?= $fecha_impresion ?>
            </td>
        </tr>
    </table>   



    <hr>

</div>



<h2><?= $titulo ?></h2>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <?php foreach($columnas as $col): ?>
                <th><?= $col ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>

    <tbody>
        <?php foreach($datos as $fila): ?>
            <tr>
                <?php foreach($fila as $valor): ?>
                    <td><?= $valor ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- lineas para totales -->

<?php if($resumen): ?>

<br><br>

<table width="40%" align="right" border="1" cellspacing="0" cellpadding="6">
    <tr>
        <td><strong>Cantidad Ventas</strong></td>
        <td align="right"><?= $resumen->cantidad_ordenes ?></td>
    </tr>

    <tr>
        <td><strong>Subtotal</strong></td>
        <td align="right"><?= number_format($resumen->subtotal_vendido,2) ?></td>
    </tr>

    <tr>
        <td><strong>IGV</strong></td>
        <td align="right"><?= number_format($resumen->total_igv,2) ?></td>
    </tr>

    <tr>
        <td><strong>Total</strong></td>
        <td align="right"><strong><?= number_format($resumen->total_general,2) ?></strong></td>
    </tr>

</table>

<?php endif; ?>
<!--  -->