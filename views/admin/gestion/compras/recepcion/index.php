
<h2 class="dashboard__heading--izquierda" id="idditulooc"><?php echo $titulo; ?></h2> 

<div class="table-wrapper">      

    <div class="table-gestion">
        <h3 class="dashboard__heading--izquierda" >Datos del Igreso</h3> 
        <!-- bloque de una sola fila grupo de 3-->
        <div class="grupo-fecha-moneda">

            <!-- Serie -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-layer-group"></i>
                    Serie
                </label>
                <select class="formulario__select" id="idserie" name="idserie">
                    <option value="">-Seleccionar-</option>

                    <?php foreach ($lista_series as $lista_serie) { ?>
                        <option
                            value="<?= $lista_serie->id ?>"
                            <?= ($idSerieSeleccionada == $lista_serie->id) ? 'selected' : '' ?>
                        >
                            <?= $lista_serie->serie ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Numero -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-hashtag"></i>
                    Número
                </label>
                <input 
                    type="text"
                    class="formulario__input"
                    id="numero"
                    value="<?= $cabecera->numero ?? '(Automático)' ?>" 
                    disabled                   
                />
            </div>       
            
            <!-- Fecha -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa fa-calendar"></i>
                    Fecha
                </label>
                <input 
                    type="date"
                    class="formulario__input"
                    id="fecha_compra"
                    name="fecha"                    
                    value="<?= $cabecera->fecha ?? $fecha ?>"  
                    required
                >
            </div>
       
        </div>

        <h3 class="dashboard__heading--izquierda" >Orden de Compra</h3> 
        <!-- bloque de una sola fila grupo de 4-->
        <div class="grupo-fecha-moneda-pago">
            
                <button class="boton boton--primary-link" id = "btngenerarOC">
                    <i class="fa-solid fa-pen-to-square"></i> Buscar O.C.
                </button> 
            
            <!-- proveedor -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fas fa-users"></i>
                    Proveedor
                </label>
                <input 
                    type="text"
                    class="formulario__input"
                    id="numero"
                    value="<?= $cabecera->numero ?? '' ?>" 
                    disabled                   
                />
            </div>   

            <!-- Orden de compra -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-hashtag"></i>
                    Número
                </label>
                <input 
                    type="text"
                    class="formulario__input"
                    id="numero"
                    value="<?= $cabecera->numero ?? '' ?>" 
                    disabled                   
                />
            </div>       
            
            <!-- Fecha -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa fa-calendar"></i>
                    Fecha
                </label>
                <input 
                    type="date"
                    class="formulario__input"
                    id="fecha_compra"
                    name="fecha"                    
                    value="<?= $cabecera->fecha ?? $fecha ?>"  
                    required
                    disabled
                >
            </div>

        </div>        

        <h3 class="dashboard__heading--izquierda" >Documentos del Proveedor</h3> 
        <!-- bloque de 5 -->
        <div class="grupo-datos-documento">

            <!-- Tipo documento -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-layer-group"></i>
                    Tipo Documento
                </label>


                <select class="formulario__select-xl" id="idtipo_documento" name ="idtipo_documento">
                <option value="" >-Seleccionar-</option>
                    <?php foreach($tipodocumentos as $tipodocumento) { ?>
                        <option value="<?php echo $tipodocumento->id; ?>" > <?php echo $tipo->nombre; ?> </option>
                    <?php }?> 
                </select>

            </div>

            <!-- Numero -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-hashtag"></i>
                    Documento
                </label>
                <input 
                    type="text"
                    class="formulario__input"
                    id="numero"                  
                                 
                />
            </div>      

            <!-- Fecha -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa fa-calendar"></i>
                    Fecha
                </label>
                <input 
                    type="date"
                    class="formulario__input"
                    id="fecha_compra"
                    name="fecha"                    
                    value="<?= $cabecera->fecha ?? $fecha ?>"  
                    required
                >
            </div>

            <!-- Guia -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa-solid fa-hashtag"></i>
                    Guia
                </label>
                <input 
                    type="text"
                    class="formulario__input"
                    id="numero"                   
                                
                />
            </div>   

            <!-- Fecha -->
            <div class="campo-inline">
                <label class="formulario__label">
                    <i class="fa fa-calendar"></i>
                    Fecha
                </label>
                <input 
                    type="date"
                    class="formulario__input"
                    id="fecha_compra"
                    name="fecha"                    
                    value="<?= $cabecera->fecha ?? $fecha ?>"  
                    required
                >
            </div>
        
        </div>        

 

        <!-- Observacion -->
        <div>
            <label for="observacion" class="formulario__label">
                <i class="fa-solid a fa-book"></i>
                Observacion
            </label>
        </div>  
        <div class="formulario__campo">  
            <input
                type = "text"
                class = "formulario__input"
                id="observacion_compra"
                value="<?= $cabecera->observacion ?? '' ?>"  
            /> 
        </div>  

    </div>  

    <div class="table-header">
        <div class="table-actions">
            <button class="boton boton--primary-link" id = "btngenerarVen">
                <i class="fa-solid fa-pen-to-square"></i> Generar Ingreso
            </button> 
            <button 
                class="boton boton--success" 
                id="ImprimirOC"
                <?= $modoEdicion ? '' : 'disabled' ?>>
                <i class="fa-solid fa-print"></i> Imprimir
            </button>
            <button class="boton boton--danger-link" id="LimpiarOC">
                <i class="fa-solid fa-trash"></i> Limpiar
            </button>            
        </div>
    </div>    

    <div class="table-body">
        
            <table id="tablaArticulosCompras"  class="table" data-table data-page-size="20">
                <thead class="table__thead">
                    <tr>               
                        <th scope='col' class="table__th">Producto</th>
                        <th scope='col' class="table__th">Cantidad</th>                     
                        <th scope='col' class="table__th">Recibida</th> 
                        <th scope='col' class="table__th">Por Recibir</th> 
                        <th scope='col' class="table__th">A Recibir</th> 
                    </tr>
                </thead>
                 <tbody class="table__tbody" id="tabla">
                    
                </tbody>
            </table>   
                 
    </div>    

    <div class="table-footer">
        <div class="table-pagination" data-table-pagination="tablaArticulosCompras"></div>  
    </div>    
</div>

