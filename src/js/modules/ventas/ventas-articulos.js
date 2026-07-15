
import Swal from "sweetalert2";
// ======================
// SELECTORES
// ======================

const tablaBody = () => document.querySelector('#tablaArticulosVentas tbody');

export function initTablaVentas(){

    const tabla = document.querySelector('#tablaArticulosVentas');
    if(!tabla) return;

    tabla.addEventListener('input', manejarCambioCantidad);
    tabla.addEventListener('change', manejarCambioCantidad);
    tabla.addEventListener('blur', manejarCambioCantidad, true);

    tabla.addEventListener('input', function(e){

        if(!e.target.classList.contains('cantidad')) return;

        manejarCambioCantidad(e);

    });

    tabla.addEventListener('click', function(e){

        const fila = e.target.closest('tr');
        if(!fila) return;

        if(e.target.classList.contains('eliminar')){

            fila.remove();
            actualizarEstado();
            actualizarTotales();
        }

    });

    tabla.addEventListener('click', function(e){

        const fila = e.target.closest('tr');
        if(!fila) return;

        const cantidadInput = fila.querySelector('.cantidad');

        if (!cantidadInput) return;

        const maxStock = parseInt(cantidadInput.getAttribute('max')) || 0;
        const tieneStock = parseInt(fila.dataset.tieneStock) || 0;

        if (!cantidadInput || !maxStock || !tieneStock ) return;

        // ======================
        // BOTON AUMENTAR
        // ======================

        if(e.target.classList.contains('aumentar')){

            if(!tieneStock){
                mostrarAlerta(
                    "warning",
                    "Artículo sin stock",
                    "Este artículo no tiene stock disponible."
                );
                return;
            }

            let nuevaCantidad = parseInt(cantidadInput.value) + 1;

            if(nuevaCantidad <= maxStock){

                cantidadInput.value = nuevaCantidad;
                actualizarTotalFila(fila);
                actualizarEstado();

            }else{

                mostrarAlerta(
                    "warning",
                    "Stock máximo alcanzado",
                    "No puedes agregar más de la cantidad en stock."
                );
            }
        }

        // ======================
        // BOTON DISMINUIR
        // ======================

        if(e.target.classList.contains('disminuir')){

            const tieneStock = parseInt(fila.dataset.tieneStock);

            const minimo = tieneStock ? 1 : 0;

            let cantidadActual = parseInt(cantidadInput.value) || 0;

            let nuevaCantidad = Math.max(minimo, cantidadActual - 1);

            cantidadInput.value = nuevaCantidad;

            actualizarTotalFila(fila);
            actualizarEstado();
        }

    });

    tabla.addEventListener('click', function(e){

        if(e.target.closest('#eliminarTodo')){
            
            if(App.ventas.articulos.length === 0) return;
            $("#buscarProducto").autocomplete("close"); 
            document.activeElement.blur(); 
            Swal.fire({
                icon: 'warning',
                title: 'Limpiar orden',
                text: 'Se eliminarán todos los artículos de la orden.',
                showCancelButton: true,
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            }).then((result)=>{

                if(result.isConfirmed){
                    resetVenta();
                }

            });

        }

    });    

}
// ======================
// AGREGAR PRODUCTO
// ======================

export function agregarProducto(producto){

    const monedaVenta = App.ventas.moneda;
    const tipoCambio = App.ventas.tipoCambio;

    const filaExistente = tablaBody()
        .querySelector(`tr[data-idproducto="${producto.id}"]`);

    // ======================
    // SI YA EXISTE
    // ======================

    if(filaExistente){

        if(!producto.tiene_stock){
            mostrarAlerta("warning","Sin stock","Este artículo no tiene stock disponible.");
            return;
        }

        const cantidadInput = filaExistente.querySelector('.cantidad');
        let nuevaCantidad = parseInt(cantidadInput.value) + 1;

        if(nuevaCantidad <= producto.stock){

            cantidadInput.value = nuevaCantidad;
            actualizarTotalFila(filaExistente);
            actualizarEstado();

        }else{

            mostrarAlerta("warning","Stock máximo alcanzado","No puedes agregar más de la cantidad en stock.");
        }

        return;
    }

    // ======================
    // NUEVA FILA
    // ======================

    let precioCalculado = producto.precio;

    if(monedaVenta != window.APP.config.moneda_base){

        precioCalculado = producto.precio * tipoCambio;

    }
    const preciosinIgv    = precioCalculado / ((App.ventas.impuesto/100)+1);
    const cantidadInicial = producto.tiene_stock ? 1 : 0;
    const minCantidad     = producto.tiene_stock ? 1 : 0;    
    const totalInicial    = cantidadInicial * precioCalculado;
    const totalsinIgv    = cantidadInicial * preciosinIgv;

    const fila = document.createElement('tr');

    fila.classList.add('table__tr');

    fila.dataset.idproducto = producto.id;
    fila.dataset.precioBase = producto.precio;    
    fila.dataset.precioActual = precioCalculado;
    fila.dataset.precioSinIgv = preciosinIgv;
    fila.dataset.stock = producto.stock_disponible;
    fila.dataset.tieneStock = producto.tiene_stock;

    fila.innerHTML = `
        <td class="descripcion">
            ${producto.value}
            ${!producto.tiene_stock ? '<span class="badge bg-danger ms-2">SIN STOCK</span>' : ''}
        </td>

        <td>
            <input 
                type="number"
                value="${cantidadInicial}"
                min="${minCantidad}"
                max="${producto.stock_disponible}"
                class="cantidad"
            >
        </td>

        <td class="unidad">${producto.unidad}</td>
        <td class="precio">${parseFloat(precioCalculado).toFixed(2)}</td>
        <td class="preciosinigv" hidden>${parseFloat(preciosinIgv).toFixed(2)}</td>
        <td class="total">${totalInicial.toFixed(2)}</td>
        <td class="totalsinigv" hidden>${totalsinIgv.toFixed(2)}</td>
        <td class="table__col-actions">
            <div class="table__acciones">
                <button class="boton boton--primary aumentar"><i class="fa fa-plus"></i></button>
                <button class="boton boton--ambar disminuir"><i class="fa fa-minus"></i></button>
                <button class="boton boton--danger eliminar"><i class="fa-solid fa-trash"></i></button>
            </div> 
        </td>
    `;

    tablaBody().appendChild(fila);

    actualizarEstado();
    actualizarTotales();
}

function actualizarEstado(){

    App.ventas.articulos = [];

    tablaBody().querySelectorAll('tr').forEach(fila => {

        App.ventas.articulos.push({

            idproducto: fila.dataset.idproducto,
            tienestock: fila.dataset.tieneStock,
            nombre: fila.querySelector('.descripcion').innerText.trim(),
            cantidad: parseFloat(fila.querySelector('.cantidad').value),
            unidad: fila.querySelector('.unidad').innerText.trim(),            
            precio_unitario: parseFloat(fila.dataset.precioActual),
            precio_unitario_sin_igv: parseFloat(fila.dataset.precioSinIgv),
            total: parseFloat(fila.querySelector('.total').innerText),
            totalsinigv: parseFloat(fila.querySelector('.totalsinigv').innerText)

        });

    });

}

function actualizarTotalFila(fila){

    const precio = parseFloat(fila.dataset.precioActual);
    const preciosinigv = parseFloat(fila.dataset.precioSinIgv);
    const cantidad = parseInt(fila.querySelector('.cantidad').value);

    if(isNaN(precio) || isNaN(cantidad)) return;

    fila.querySelector('.total').textContent =
        (precio * cantidad).toFixed(2);

    fila.querySelector('.totalsinigv').textContent =
        (preciosinigv * cantidad).toFixed(2);

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
    
    subtotalGeneral    = totalGeneral / ((App.ventas.impuesto/100)+1);
    igvGeneral = totalGeneral - subtotalGeneral;
    App.ventas.totales.subtotal = subtotalGeneral;
    App.ventas.totales.impuesto = igvGeneral;
    App.ventas.totales.total = totalGeneral;

    document.getElementById('subtotalVenta').textContent =
        subtotalGeneral.toFixed(2);
    document.getElementById('totalVentaIgv').textContent =
        igvGeneral.toFixed(2);
    document.getElementById('totalVenta').textContent =
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



function manejarCambioCantidad(e){

    if(!e.target.classList.contains('cantidad')) return;

    const fila = e.target.closest('tr');
    const cantidadInput = e.target;

    let cantidad = parseFloat(cantidadInput.value);
    const maxStock = parseFloat(cantidadInput.getAttribute('max'));
    

    if (!cantidad || !maxStock ) return;totalsinigv
    
    if(isNaN(cantidad) || cantidad < 0){
        cantidad = 0;
    }

    if(cantidad > maxStock){

        mostrarAlerta(
            "warning",
            "Stock máximo alcanzado",
            "No puedes ingresar una cantidad mayor al stock disponible."
        );

        cantidad = maxStock;
        cantidadInput.value = maxStock;
    }

    actualizarTotalFila(fila);
    actualizarEstado();
}


export function resetVenta(){

    App.ventas.articulos = [];

    App.ventas.totales = {
        subtotal: 0,
        impuesto: 0,
        total: 0
    };

    // limpiar tabla
    const tbody = document.querySelector('#tablaArticulosVentas tbody');
    if(tbody) tbody.innerHTML = '';

    const clienteInput = document.querySelector("#buscarCliente");
    if(clienteInput) clienteInput.value = "";

    // limpiar total visual
    const totalVenta = document.getElementById('totalVenta');
    if(totalVenta) totalVenta.textContent = "0.00";

    const igvVenta = document.getElementById('totalVentaIgv');
    if(igvVenta) igvVenta.textContent = "0.00";

    const subtotalVenta = document.getElementById('subtotalVenta');
    if(subtotalVenta) subtotalVenta.textContent = "0.00";

    document.getElementById('idmoneda').value = window.APP.config.moneda_base;

    document.getElementById('buscarProducto')?.focus();

}

export function recalcularPreciosVenta(){

    const monedaVenta = App.ventas.moneda;
    const tipoCambio = App.ventas.tipoCambio;
    const monedaBase = window.APP.config.moneda_base;

    document
    .querySelectorAll('#tablaArticulosVentas tbody tr')
    .forEach(fila => {

        const precioBase = parseFloat(fila.dataset.precioBase);
        const cantidad = parseFloat(
            fila.querySelector('.cantidad').value
        );

        let precioNuevo = precioBase;
        let precioNuevosinIgv =  precioNuevo / ((App.ventas.impuesto/100)+1);

        if(monedaVenta != monedaBase){
            precioNuevo = precioBase * tipoCambio;
            precioNuevosinIgv =  precioNuevo / ((App.ventas.impuesto/100)+1);
        }

        // actualizar dataset
        fila.dataset.precioActual = precioNuevo;
        fila.dataset.precioSinIgv = precioNuevosinIgv;

        // actualizar precio visible
        fila.querySelector('.precio').textContent =
            precioNuevo.toFixed(2);
        fila.querySelector('.preciosinigv').textContent =
            precioNuevosinIgv.toFixed(2);
        // recalcular total
        fila.querySelector('.total').textContent =
            (precioNuevo * cantidad).toFixed(2);
        fila.querySelector('.totalsinigv').textContent =
            (precioNuevosinIgv * cantidad).toFixed(2);
    });

    actualizarTotales();
    actualizarEstado();
}
export function cargarDetalleEdicion(detalle = []) {

    if (!Array.isArray(detalle) || detalle.length === 0) {
        return;
    }

    // Limpiar arreglo actual
    App.ventas.articulos = [];

    detalle.forEach(item => {

        const producto = {
            id: item.idproducto,
            value: item.nombre, 
            unidad: item.unidad,
            precio: parseFloat(item.precio_igv_origen), 
            precio_origen: parseFloat(item.precio_origen), 
            subtotal_origen: parseFloat(item.subtotal_origen), 
            total_origen: parseFloat(item.total_origen), 
            codigo: item.codigo || '',            
            tiene_stock: 1,
            stock_disponible: 999999,
            stock_comprometido: 0
        };
  
        agregarProducto(producto);
        
        const fila = document.querySelector(
            `#tablaArticulosVentas tbody tr[data-idproducto="${item.idproducto}"]`
        );

        if (!fila) return;

        const inputCantidad = fila.querySelector('.cantidad');
        if (inputCantidad) {
            inputCantidad.value = parseFloat(item.cantidad);
        }
        
        fila.dataset.precioBase   = parseFloat(item.precio_igv_origen);
        fila.dataset.precioActual = parseFloat(item.precio_igv_origen);
        fila.dataset.precioSinIgv = parseFloat(item.precio_igv_origen)/ ((App.ventas.impuesto/100)+1); 
        

        const tdPrecio = fila.querySelector('.precio');
        if (tdPrecio) {
            tdPrecio.textContent =
                parseFloat(item.precio_igv_origen).toFixed(2);
        }

        const tdPreciosinigv = fila.querySelector('.preciosinigv');
        if (tdPreciosinigv) {
            tdPreciosinigv.textContent =
                parseFloat(item.precio_igv_origen/((App.ventas.impuesto/100)+1)).toFixed(2);
        }

        const tdTotal = fila.querySelector('.total');
        if (tdTotal) {
            tdTotal.textContent =
                parseFloat(item.total_origen).toFixed(2);
        }
        const tdTotalsinigv = fila.querySelector('.totalsinigv');
        if (tdTotalsinigv) {
            tdTotalsinigv.textContent =
                parseFloat(item.total_origen/((App.ventas.impuesto/100)+1)).toFixed(2);
        }
    });

    actualizarEstado();
    actualizarTotales();
}