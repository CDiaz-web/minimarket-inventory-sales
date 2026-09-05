<!DOCTYPE html>


<html lang="es">
<head>
<meta charset="UTF-8">
<title>Movimiento Inventario #<?= $cabecera->numero_documento ?></title>
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

  <h1>Movimiento Inventario N° <?= $cabecera->numero_documento ?></h1>
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
     <strong>Documento:</strong> <?=  $cabecera->documento_entrada ?><br>
     <strong>Fecha:</strong> <?= $cabecera->fecha ?><br>
     <strong>Orden de Compra:</strong> <?= $cabecera->orden_compra ?><br>
     <strong>Tienda:</strong> <?= $cabecera->tienda ?><br>     
     <strong>Proveedor:</strong> <?=  $cabecera->ruc . ' ' . $cabecera->proveedor ?><br>
     <strong>Estado:</strong> <?= $cabecera->estado ?><br>

     <strong>Usuario:</strong> <?= $cabecera->usuario ?><br>
     
   
  </p>

  <table>
    <thead>
      <tr>
        <th>Código</th>
        <th>Producto</th>
        <th>Unidad</th>
        <th>Cantidad</th> 
      </tr>
    </thead>
    <tbody>
      <?php foreach ($detalle as $d): ?>
      <tr>
        <td><?= $d->codigo ?></td>
        <td><?= $d->nombre ?></td>
        <td><?= $d->unidad ?></td>
        <td><?= number_format($d->cantidad, 2) ?></td>        
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
 
  <p>
     <strong>Observación:</strong> <?= $cabecera->observacion ?> 
  </p>


</body>
</html>