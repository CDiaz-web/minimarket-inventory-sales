<form id="formEditarMovimiento">
  <table class="table table-sm table-bordered">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Costo</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($detalle as $item): ?>
            <tr>
                <td><?= $item->producto ?></td>
                <td>
                <input type="number"
                        name="cantidad[]"
                        class="form-control cantidad"
                        value="<?= htmlspecialchars($item->cantidad) ?>"
                        min="1"
                        step="0.01">  
                <td>
                    
                    <?php if ($item->tipo === 'COMPRA'): ?>
                        <input type="number"
                            name="costo[]"
                            class="form-control costo"
                            value="<?= htmlspecialchars($item->costo_unitario ?? '') ?>"
                            min="0"
                            step="0.01">
                    <?php else: ?>
                        <input type="number"
                            class="form-control"
                            value="<?= htmlspecialchars($item->costo_unitario  ?? '') ?>"                          
                            readonly>
                    <?php endif; ?>
                </td>
                <td>
                    <input type="number"
                            class="form-control total"
                            value="<?= number_format($item->cantidad * $item->costo_unitario, 2) ?>"
                            readonly>
              
                
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-primary btn-guardar"data-id="<?= $item->iddetalle ?>">💾</button>
                </td>
            </tr>
        <?php endforeach; ?>

    </tbody>
  </table>
</form>
