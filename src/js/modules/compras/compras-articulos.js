
import Swal from "sweetalert2";
// ======================
// SELECTORES
// ======================

const tablaBody = () => document.querySelector('#tablaArticulosCompras tbody');

// ======================
// AGREGAR PRODUCTO
// ======================

export function agregarProducto(producto){
    
    const monedaCompra = App.compras.moneda;
    const tipoCambio = App.compras.tipoCambio;

    const filaExistente = tablaBody()
        .querySelector(`tr[data-idproducto="${producto.id}"]`);

    // ======================
    // SI YA EXISTE
    // ======================

    if(filaExistente){

        const cantidadInput = filaExistente.querySelector('.cantidad-compra');
        let nuevaCantidad = parseInt(cantidadInput.value) + 1;
   

        cantidadInput.value = nuevaCantidad;
        actualizarTotalFila(filaExistente);
        actualizarEstado();

        return;
    }

    // ======================
    // NUEVA FILA
    // ======================

    let costoCalculado = producto.costo;
    

    if(monedaCompra != window.APP.config.moneda_base){

        costoCalculado = producto.costo * tipoCambio;

    }

    const cantidadInicial = 1;    
  
    const costosinIgv    = costoCalculado / ((App.compras.impuesto/100)+1);
    const totalInicial    = cantidadInicial * costoCalculado;
    const totalInicial_igv    = cantidadInicial * costosinIgv;

    const fila = document.createElement('tr');

    fila.classList.add('table__tr');

    fila.dataset.idproducto = producto.id;
    fila.dataset.precioBaseOriginal = producto.costo;
    fila.dataset.precioActual = costoCalculado;
    fila.dataset.costosinIgv = costosinIgv;

    fila.innerHTML = `
        <td class="descripcion">
            ${producto.value}
            
        </td>

        <td>
            <input 
                type="number"
                value="${cantidadInicial}"         
                class="cantidad-compra"
                style="width:90px;text-align:center;"
            >
        </td>

        <td class="unidad">${producto.unidad}</td>      

        <td>
            <input 
                type="number"
                value="${parseFloat(costoCalculado).toFixed(2)}"         
                class="costo"
                style="width:120px;text-align:right;"
            >
        </td>
        <td class="costosinigv" hidden>${parseFloat(costosinIgv).toFixed(2)}</td>    
        <td class="total" style="width:120px;text-align:right;" >${totalInicial.toFixed(2)}</td>
        <td class="totalsinigv style="width:120px;text-align:right;" hidden>${totalInicial_igv.toFixed(2)}</td>
        <td class="table__col-actions">
            <div class="table__acciones">
                <button class="boton boton--primary aumentar-compra"><i class="fa fa-plus"></i></button>
                <button class="boton boton--ambar disminuir-compra"><i class="fa fa-minus"></i></button>
                <button class="boton boton--danger eliminar-compra"><i class="fa-solid fa-trash"></i></button>
            </div> 
        </td>
    `;

    tablaBody().appendChild(fila);

    actualizarEstado();
    actualizarTotales();
}

function actualizarEstado(){

    App.compras.articulos = [];

    tablaBody().querySelectorAll('tr').forEach(fila => {

        App.compras.articulos.push({

            idproducto: fila.dataset.idproducto,            
            nombre: fila.querySelector('.descripcion').innerText.trim(),
            cantidad: parseFloat(fila.querySelector('.cantidad-compra').value),
            unidad: fila.querySelector('.unidad').innerText.trim(),
            costo: parseFloat(fila.dataset.costosinIgv),
            costo_igv: parseFloat(fila.dataset.precioActual),
            total: parseFloat(fila.querySelector('.totalsinigv').innerText),
            total_igv: parseFloat(fila.querySelector('.total').innerText)

        });

    });

}

function actualizarTotalFila(fila){

    const precio = parseFloat(fila.dataset.precioActual);
    const costoSinIgv = parseFloat(fila.dataset.costosinIgv);
    const cantidad = parseInt(fila.querySelector('.cantidad-compra').value);

    if(isNaN(precio) || isNaN(cantidad)) return;

    fila.querySelector('.total').textContent =  (precio * cantidad).toFixed(2);
    fila.querySelector('.totalsinigv').textContent =  (costoSinIgv * cantidad).toFixed(2);

    actualizarTotales();
}

function actualizarTotales(){

    let totalGeneral = 0;
    let subtotalGeneral = 0;
    let igvGeneral = 0;

    tablaBody().querySelectorAll('tr').forEach(fila => {

        totalGeneral += parseFloat(
            fila.querySelector('.total').innerText
        );

    });

    subtotalGeneral    = totalGeneral / ((App.compras.impuesto/100)+1);
    igvGeneral = totalGeneral - subtotalGeneral;
    
    App.compras.totales.subtotal = subtotalGeneral;
    App.compras.totales.impuesto = igvGeneral;
    App.compras.totales.total = totalGeneral;

    document.getElementById('subtotalCompra').textContent =
        subtotalGeneral.toFixed(2);
    document.getElementById('totalIgv').textContent =
        igvGeneral.toFixed(2);
    document.getElementById('totalCompra').textContent =
        totalGeneral.toFixed(2);
}





function mostrarAlerta(icono, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        title: titulo,
        text: mensaje,
        confirmButtonText: "Entendido",
        timer: 2000
    });
}


export function initTablaCompras(){

    const tabla = document.querySelector('#tablaArticulosCompras');
    if(!tabla) return;

    tabla.addEventListener('input', manejarCambioCantidad);
    tabla.addEventListener('change', manejarCambioCantidad);
    tabla.addEventListener('blur', manejarCambioCantidad, true);

    tabla.addEventListener('input', function(e){

        if(!e.target.classList.contains('cantidad-compra')) return;

        manejarCambioCantidad(e);

    });

    tabla.addEventListener('click', function(e){

        const fila = e.target.closest('tr');
        if(!fila) return;

        if(e.target.classList.contains('eliminar-compra')){
            
            fila.remove();        
            actualizarEstado();        
            actualizarTotales();
        
        }

    }); 
 

    tabla.addEventListener('click', function(e){

        const fila = e.target.closest('tr');
        if(!fila) return;

        const cantidadInput = fila.querySelector('.cantidad-compra');    
    
        if (!cantidadInput ) return;
        // ======================
        // BOTON AUMENTAR
        // ======================

        if(e.target.classList.contains('aumentar-compra')){

            let nuevaCantidad = parseInt(cantidadInput.value) + 1;
        
            cantidadInput.value = nuevaCantidad;
            actualizarTotalFila(fila);
            actualizarEstado();

        }

        // ======================
        // BOTON DISMINUIR
        // ======================

        if(e.target.classList.contains('disminuir-compra')){

            let cantidadActual = parseInt(cantidadInput.value) || 0;

            let nuevaCantidad = Math.max(1, cantidadActual - 1);

            cantidadInput.value = nuevaCantidad;

            actualizarTotalFila(fila);
            actualizarEstado();
        }

    });


}

function manejarCambioCantidad(e){

    const fila = e.target.closest('tr');
    if(!fila) return;

    let cantidad = parseFloat(e.target.value);

    if(isNaN(cantidad) || cantidad < 0){
        cantidad = 0;
        e.target.value = 0;
    }

    actualizarTotalFila(fila);
    actualizarEstado();
}

export function resetCompras(){

    App.compras.articulos = [];

    App.compras.totales = {
        subtotal: 0,
        impuesto: 0,
        total: 0
    };

    App.compras.idproveedor = null;

    const tbody = document.querySelector('#tablaArticulosCompras tbody');
    if(tbody) tbody.innerHTML = '';

    const totalCompra = document.getElementById('totalCompra');
    if(totalCompra) totalCompra.textContent = "0.00";

    const igvCompra = document.getElementById('totalIgv');
    if(igvCompra) igvCompra.textContent = "0.00";

    const subtotalCompra = document.getElementById('subtotalCompra');
    if(subtotalCompra) subtotalCompra.textContent = "0.00";

    document.getElementById('buscarProveedor').value = '';

    document.getElementById('observacion_compra').value = '';    

    document.getElementById('idmoneda').value = window.APP.config.moneda_base;

    document.getElementById('buscarProveedor')?.focus();

}


export function recalcularPreciosCompra(){

    if(!App.compras.tipoCambio){
        console.warn('Sin tipo de cambio válido');
        return;
    }

    const monedaCompra = App.compras.moneda;
    const tipoCambio = App.compras.tipoCambio;
    const monedaBase = window.APP.config.moneda_base;
    const tasa = App.compras.impuesto / 100;

    document
    .querySelectorAll('#tablaArticulosCompras tbody tr')
    .forEach(fila => {

        const inputCosto = fila.querySelector('.costo');
        const cantidad = parseFloat(
            fila.querySelector('.cantidad-compra').value
        ) || 0;

        let precioBaseOriginal = parseFloat(fila.dataset.precioBaseOriginal);

        let precioNuevo = precioBaseOriginal;

        if(monedaCompra != monedaBase){
            precioNuevo = precioBaseOriginal * tipoCambio;
        }

        fila.dataset.precioActual = precioNuevo;

        // costo con IGV
        if(inputCosto){
            inputCosto.value = precioNuevo.toFixed(2);
        }

        // 🔥 COSTO SIN IGV (lo que te faltaba)
        const costoSinIgv = precioNuevo / (1 + tasa);

        fila.dataset.costosinIgv = costoSinIgv;

        fila.querySelector('.costosinigv').textContent =
            costoSinIgv.toFixed(2);

        // total
        fila.querySelector('.total').textContent =
            (precioNuevo * cantidad).toFixed(2);

            // total
        fila.querySelector('.totalsinigv').textContent =
            (costoSinIgv * cantidad).toFixed(2);

    });

    actualizarTotales();
    actualizarEstado();
}


export function cargarDetalleEdicion(detalle = []) {

    if (!Array.isArray(detalle) || detalle.length === 0) {
        return;
    }

    // Limpiar arreglo actual
    App.compras.articulos = [];

    detalle.forEach(item => {

        const producto = {
            id: item.idproducto,
            value: item.producto || item.nombre || item.descripcion,
            unidad: item.unidad,
            costo: parseFloat(item.costo_igv_origen || item.costo_origen),
            codigo: item.codigo || ''
        };

        // Agregar producto a la tabla
        agregarProducto(producto);

        // Buscar la fila recién creada
        const fila = document
            .querySelector(`#tablaArticulosCompras tbody tr[data-idproducto="${item.idproducto}"]`);

        if (!fila) return;

        // Colocar cantidad real
        const inputCantidad = fila.querySelector('.cantidad-compra');
        inputCantidad.value = parseFloat(item.cantidad);

        // Colocar costo con IGV real
        const inputCosto = fila.querySelector('.costo');
        if (inputCosto) {
            inputCosto.value = parseFloat(item.costo_igv_origen).toFixed(2);
        }

        // Actualizar datasets
        fila.dataset.precioActual = parseFloat(item.costo_igv_origen);
        fila.dataset.precioBaseOriginal = parseFloat(item.costo_igv_origen);
        fila.dataset.costosinIgv = parseFloat(item.costo_origen);

        // Recalcular totales visuales
        fila.querySelector('.costosinigv').textContent =
            parseFloat(item.costo_origen).toFixed(2);

        fila.querySelector('.total').textContent =
            parseFloat(item.total_origen).toFixed(2);

        fila.querySelector('.totalsinigv').textContent =
            parseFloat(item.subtotal_origen).toFixed(2);
    });

    // Reconstruir estado global
    actualizarEstado();
    actualizarTotales();
}

document.addEventListener('input', (e) => {

    if(e.target.classList.contains('costo')){

        const fila = e.target.closest('tr');

        const nuevoCosto = parseFloat(e.target.value) || 0;

        // ACTUALIZAR BASE REAL
        fila.dataset.precioBaseOriginal = nuevoCosto;

        recalcularPreciosCompra();
    }

});