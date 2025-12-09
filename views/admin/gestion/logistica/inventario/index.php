<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
   <?php 
        include_once __DIR__ . '/../../../../templates/alertas.php';          
    ?>
<div class="dashboard__contenedor">

    <a class="dashboard__boton-agregar" href="/admin/gestion/logistica/inventario/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir
    </a>


    <form method="GET" class="dashboard__filtros">
        <label for="anio">Año:</label>
        <input 
            type="number" 
            id="anio" 
            name="anio" 
            value="<?php echo $anio; ?>" 
            min="2000" 
            max="<?php echo date('Y')+1; ?>" 
        />

        <label for="mes">Mes:</label>
        <select id="mes" name="mes">
            <?php 
                $meses = [
                    1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril",
                    5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto",
                    9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
                ];
                foreach($meses as $num => $nombre): 
            ?>
                <option value="<?php echo $num; ?>" <?php echo ($mes == $num) ? 'selected' : ''; ?>>
                    <?php echo $nombre; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="dashboard__boton-filtrar">
            <i class="fa-solid fa-magnifying-glass"></i> Filtrar
        </button>
    </form>


    <input
        type = "text"
        class = "dashboard__buscar"
        id="buscar"
        name="buscar"
        placeholder="Buscar..."                        
    /> 
</div>
<br>
<div class="dashboard__contenedor"  id="table_box_master">
    <?php if(!empty($inventarios)) { ?>
        <table class="table" id ="tabla" border="0">
            <thead class="table__thead">
                <tr>      
                    <th scope='col' class="table__th">Documento</th>    
                    <th scope='col' class="table__th">Fecha</th>    
                    <th scope='col' class="table__th">Tienda</th> 
                    <th scope='col' class="table__th">Movimiento</th>  
                    <th scope='col' class="table__th">Tda. Destino</th> 
                    <th scope='col' class="table__th">Estado</th> 
                    <th scope='col' class="table__th"></th>
                </tr>
            </thead>
            <tbody class="table__tbody" id="tabla">
                <?php foreach($inventarios as $inventario) { ?>
                    <tr class="table__tr">            
                        <td class="table__td">
                            <?php echo $inventario->documento;?>
                        </td>    
                        <td class="table__td">
                            <?php echo $inventario->fecha;?>
                        </td>
                        <td class="table__td">
                            <?php echo $inventario->tienda_origen;?>
                        </td>  
                        <td class="table__td">
                            <?php echo $inventario->movimiento;?>
                        </td>
                        <td class="table__td">
                            <?php echo $inventario->tienda_destino;?>
                        </td>     
                        <td class="table__td">
                            <?php echo $inventario->estado;?>
                        </td>                     
                        <td class="table__td--acciones">
                            <button class="table__mantenimiento table__mantenimiento--editar btnEditarMovimiento"  data-id="<?php echo $inventario->id ?>">
                                <i class="fa-solid fa-user-pen  table____mantenimient--icono"></i> 
                            </button>

                            <button class="table__mantenimiento table__mantenimiento--editar btn-imprimir"  data-id="<?php echo $inventario->id ?>">
                                <i class="fa fa-print  table____mantenimient--icono"></i> 
                            </button>                            

                            <form id ="frEliminar<?php echo $inventario->id; ?>"  method="POST" action="/admin/gestion/logistica/inventario/anular" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $inventario->id; ?>">
                                <button class="table__mantenimiento table__mantenimiento--eliminar"  type="button" data-id="<?php echo $inventario->id; ?>">
                                    <i class="fa-solid fa-circle-xmark  table____mantenimient--icono"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No hay Movimientos Registrados en el periodo</p>
    <?php } ?>
</div>
                 
<div id="index_native_master" class="box"></div>

<!-- Modal Editar Movimiento -->
<div class="modal-inv fade" id="modalEditarMovimiento" tabindex="-1" aria-labelledby="modalEditarMovimientoLabel" aria-hidden="true">
  <div class="modal-inv-dialog">
    <div class="modal-inv-content">
      
      <div class="modal-inv-header bg-primary text-white">
        <h5 class="modal-inv-title" id="modalEditarMovimientoLabel">Editar Movimiento</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-inv-body">
        <div id="contenidoEditarMovimiento" class="text-center py-4">
          <i class="fas fa-spinner fa-spin fa-2x"></i>
          <p>Cargando detalles...</p>
        </div>
      </div>

      <div class="modal-inv-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>

    </div>
  </div>
</div>


