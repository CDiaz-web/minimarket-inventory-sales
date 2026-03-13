
<h2 class="dashboard__heading"><?php echo $titulo_uno; ?></h2>

<!-- 
<div class="reportes-grid">

<?php foreach($reportes as $reporte): ?>

<a 
href="<?= $reporte->vista ?>" 
class="reporte-card">

    <i class="<?= $reporte->icono ?>"></i>

    <span>
        <?= $reporte->nombre ?>
    </span>

</a>

<?php endforeach; ?>

</div> -->

<div class="reportes">

    <?php foreach($reportes as $reporte): ?>

        <a href="<?= $reporte->vista ?>" class="reporte-card">
            
            <div class="reporte-card__icon">
                <i class="<?= $reporte->icono ?>"></i>
            </div>

            <div class="reporte-card__info">
                <h3><?= $reporte->nombre ?></h3>
                <p>Ver reporte</p>
            </div>

            <div class="reporte-card__arrow">
                <i class="fa-solid fa-chevron-right"></i>
            </div>

        </a>

    <?php endforeach; ?>

</div>