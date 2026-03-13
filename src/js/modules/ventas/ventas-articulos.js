
import Swal from "sweetalert2";
// ======================
// SELECTORES
// ======================

const tablaBody = () => document.querySelector('#tablaArticulos tbody');

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

    const cantidadInicial = producto.tiene_stock ? 1 : 0;
    const minCantidad     = producto.tiene_stock ? 1 : 0;
    // const totalInicial    = cantidadInicial * producto.precio;
    const totalInicial    = cantidadInicial * precioCalculado;

    const fila = document.createElement('tr');

    fila.classList.add('table__tr');

    fila.dataset.idproducto = producto.id;
    fila.dataset.precioBase = producto.precio;
    // fila.dataset.precioActual = producto.precio;
    fila.dataset.precioActual = precioCalculado;
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
        <td class="total">${totalInicial.toFixed(2)}</td>

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

            // precio_unitario: parseFloat(fila.querySelector('.precio').innerText),
            precio_unitario: parseFloat(fila.dataset.precioActual),
            total: parseFloat(fila.querySelector('.total').innerText)

        });

    });
   
}

function actualizarTotalFila(fila){

    const precio = parseFloat(fila.dataset.precioActual);
    const cantidad = parseInt(fila.querySelector('.cantidad').value);

    if(isNaN(precio) || isNaN(cantidad)) return;

    fila.querySelector('.total').textContent =
        (precio * cantidad).toFixed(2);

    actualizarTotales();
}

function actualizarTotales(){

    let totalGeneral = 0;

    tablaBody().querySelectorAll('tr').forEach(fila => {

        totalGeneral += parseFloat(
            fila.querySelector('.total').innerText
        );

    });

    App.ventas.totales.total = totalGeneral;

    document.getElementById('totalVenta').textContent =
        totalGeneral.toFixed(2);
}

document.addEventListener('click', function(e){

    const fila = e.target.closest('tr');
    if(!fila) return;

    // eliminar

    if(e.target.classList.contains('eliminar')){

        fila.remove();
        actualizarEstado();
        actualizarTotales();
    }

});

document.addEventListener('click', function(e){

    const fila = e.target.closest('tr');
    if(!fila) return;

    const cantidadInput = fila.querySelector('.cantidad');
    const maxStock = parseInt(cantidadInput.getAttribute('max'));
    const tieneStock = parseInt(fila.dataset.tieneStock);

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

function mostrarAlerta(icono, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        title: titulo,
        text: mensaje,
        confirmButtonText: "Entendido",
        timer: 2000
    });
}

document.addEventListener('input', manejarCambioCantidad);
document.addEventListener('change', manejarCambioCantidad);
document.addEventListener('blur', manejarCambioCantidad, true);

function manejarCambioCantidad(e){

    if(!e.target.classList.contains('cantidad')) return;

    const fila = e.target.closest('tr');
    const cantidadInput = e.target;

    let cantidad = parseFloat(cantidadInput.value);
    const maxStock = parseFloat(cantidadInput.getAttribute('max'));

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

document.addEventListener('click', function(e){

    if(e.target.closest('#eliminarTodo')){
        
        if(App.ventas.articulos.length === 0) return;
        $("#buscarProducto").autocomplete("close"); // cerrar autocomplete
        document.activeElement.blur(); // 👈 quitar focus
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

export function resetVenta(){

    // limpiar estado
    App.ventas.articulos = [];

    App.ventas.totales = {
        subtotal: 0,
        impuesto: 0,
        total: 0
    };

    // limpiar tabla
    const tbody = document.querySelector('#tablaArticulos tbody');
    if(tbody) tbody.innerHTML = '';

    // limpiar total visual
    const totalVenta = document.getElementById('totalVenta');
    if(totalVenta) totalVenta.textContent = "0.00";

    document.getElementById('buscarProducto')?.focus();

}

export function recalcularPreciosVenta(){

    const monedaVenta = App.ventas.moneda;
    const tipoCambio = App.ventas.tipoCambio;
    const monedaBase = window.APP.config.moneda_base;

    document
    .querySelectorAll('#tablaArticulos tbody tr')
    .forEach(fila => {

        const precioBase = parseFloat(fila.dataset.precioBase);
        const cantidad = parseFloat(
            fila.querySelector('.cantidad').value
        );

        let precioNuevo = precioBase;

        if(monedaVenta != monedaBase){
            precioNuevo = precioBase * tipoCambio;
        }

        // actualizar dataset
        fila.dataset.precioActual = precioNuevo;

        // actualizar precio visible
        fila.querySelector('.precio').textContent =
            precioNuevo.toFixed(2);

        // recalcular total
        fila.querySelector('.total').textContent =
            (precioNuevo * cantidad).toFixed(2);

    });

    actualizarTotales();
    actualizarEstado();
}