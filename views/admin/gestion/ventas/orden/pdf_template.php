<!DOCTYPE html>


<html lang="es">
<head>
<meta charset="UTF-8">
<title>Orden de Venta #<?= $cabecera->numero ?></title>
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
  h1 { text-align: center; margin-bottom: 5px; }
  .info { margin-bottom: 15px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: 1px solid #999; padding: 6px; text-align: left; }
  th { background: #f0f0f0; }
  .totales { text-align: right; font-weight: bold; }
</style>
</head>
<body>

  <h1>Orden de Venta N° <?= $cabecera->numero ?></h1>
  <p>

      <?php
        $rutaLogo = realpath(__DIR__ . '/../../../../../public/img/' . $empresa->logo . '.png');
        $logoBase64 = '';

        if ($rutaLogo && file_exists($rutaLogo)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($rutaLogo));
        }
      ?>

      <?php if ($logoBase64): ?>
          <div style="text-align:center; margin-bottom: 20px;">
              <img src="<?= $logoBase64 ?>" alt="Logo de la empresa" style="width: 120px; height: auto;">
          </div>
      <?php endif;?>


     <strong>Empresa:</strong> <?= $empresa->nombre ?><br>
     <strong>Tienda:</strong> <?= $cabecera->tienda ?><br>
     <strong>Direccion:</strong> <?= $cabecera->direccion ?><br>
     <strong>Señores:</strong> <?= $cabecera->cliente ?><br>
     <strong>Fecha:</strong> <?= $cabecera->fecha ?><br>
     <strong>Direccion:</strong> <?= $cabecera->direccion_cliente ?><br>
     <strong>Ruc:</strong> <?= $cabecera->documento ?><br>
     <strong>Usuario:</strong> <?= $cabecera->usuario ?><br>
     <strong>Estado:</strong> <?= $cabecera->estado ?><br>
     <strong>Moneda:</strong> <?= $cabecera->moneda ?><br>
     <!-- <strong>Observación:</strong> <?= $cabecera->observacion ?> -->
  </p>

  <table>
    <thead>
      <tr>
        <th>Código</th>
        <th>Producto</th>
        <th>Unidad</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($detalle as $d): ?>
      <tr>
        <td><?= $d->codigo ?></td>
        <td><?= $d->nombre ?></td>
        <td><?= $d->unidad ?></td>
        <td><?= number_format($d->cantidad, 2) ?></td>
        <td><?= number_format($d->precio_unitario, 2) ?></td>
        <td><?= number_format($d->total, 2) ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p>     
     <strong>Subtotal:</strong> <?= $cabecera->simbolo . " " . $cabecera->subtotal ?><br>
     <strong>IGV:</strong> <?= $cabecera->simbolo . " " .  $cabecera->igv ?><br>
     <strong>Total:</strong> <?= $cabecera->simbolo . " " .  $cabecera->total ?><br>
     <strong>Observación:</strong> <?= $cabecera->observacion ?> 
  </p>


</body>
</html>
