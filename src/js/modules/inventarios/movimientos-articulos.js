import { initProductosMovimientos, cargarProductos } from './movimientos-productos.js';
import Swal from "sweetalert2";
// ======================
// SELECTORES
// ======================

const tablaBody = () => document.querySelector('#tablaArticulosMovimientos tbody');

export function initTablaMovimientos(){

    const tabla = document.querySelector('#tablaArticulosMovimientos');
    if(!tabla) return;

    tabla.addEventListener('input', manejarCambioCantidad);
    tabla.addEventListener('change', manejarCambioCantidad);
    tabla.addEventListener('blur', manejarCambioCantidad, true);

    tabla.addEventListener('input', function(e){

        if(!e.target.classList.contains('cantidad')) return;

        manejarCambioCantidad(e);

    });

    tabla.addEventListener('click', function(e){

        if(e.target.closest('#eliminarTodo')){
            
            if(App.movimientos.articulos.length === 0) return;
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
                    resetMovimientos();
                }

            });

        }

    });

    tabla.addEventListener('click', function(e){

        const fila = e.target.closest('tr');
        if(!fila) return;

        if(e.target.closest('.mov-eliminar')){

            fila.remove();
            actualizarEstadoMov();
        }

    });

    tabla.addEventListener('click', function(e){

        const fila = e.target.closest('tr');
        if(!fila) return;

        const cantidadInput = fila.querySelector('.cantidad');
        const maxStock = parseInt(cantidadInput.getAttribute('max'));
        const tieneStock = parseInt(fila.dataset.tieneStock);


        if(esMovimientoResta()){
            if (!cantidadInput || !maxStock || !tieneStock ) return;     
        }

        if(e.target.closest('.mov-aumentar')){

            if(esMovimientoResta() && !tieneStock){
                mostrarAlerta(
                    "warning",
                    "Artículo sin stock",
                    "Este artículo no tiene stock disponible."
                );
                return;
            }

            let nuevaCantidad = parseInt(cantidadInput.value) + 1;

            // Solo valida stock para movimientos RESTA
            if(esMovimientoResta() && nuevaCantidad > maxStock){

                mostrarAlerta(
                    "warning",
                    "Stock máximo alcanzado",
                    "No puedes agregar más de la cantidad en stock."
                );

                return;
            }

            cantidadInput.value = nuevaCantidad;
            actualizarEstadoMov();
        }



        // ======================
        // BOTON DISMINUIR
        // ======================

        if(e.target.closest('.mov-disminuir')){

            const tieneStock = parseInt(fila.dataset.tieneStock);

            const minimo = tieneStock ? 1 : 0;

            let cantidadActual = parseInt(cantidadInput.value) || 0;

            let nuevaCantidad = Math.max(minimo, cantidadActual - 1);

            cantidadInput.value = nuevaCantidad;
            
            actualizarEstadoMov();
        }

    });    


}

export function esMovimientoResta(){

    const select = document.getElementById('idtipo');

    if(!select?.value) return false;

    const opcion =
        select.options[select.selectedIndex];

    return opcion.dataset.accion === 'Resta';
}
// ======================
// AGREGAR PRODUCTO
// ======================

export function agregarProducto(producto){

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


        if( !esMovimientoResta() || nuevaCantidad <= producto.stock_disponible){

            cantidadInput.value = nuevaCantidad;            
            actualizarEstadoMov();

        }else{            
            mostrarAlerta("warning","Stock máximo alcanzado","No puedes agregar más de la cantidad en stock.");
        }

        return;
    }

    // ======================
    // NUEVA FILA
    // ======================  


    let cantidadInicial = 1;
    let minCantidad = 1;

    if(esMovimientoResta() && !producto.tiene_stock){
        cantidadInicial = 0;
        minCantidad = 0;
    }
 
    const fila = document.createElement('tr');

    fila.classList.add('table__tr');
    fila.dataset.idproducto = producto.id;    
    fila.dataset.stock = producto.stock_disponible;
    fila.dataset.tieneStock = producto.tiene_stock;

    const atributoMax = esMovimientoResta()
    ? `max="${producto.stock_disponible}"`
    : '';

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
                ${atributoMax}
                class="cantidad"
            >
        </td>
        <td class="stock">${producto.stock_disponible}</td>  
        <td class="unidad">${producto.unidad}</td>        
        <td class="table__col-actions">
            <div class="table__acciones">
                <button class="boton boton--primary mov-aumentar"><i class="fa fa-plus"></i></button>
                <button class="boton boton--ambar mov-disminuir"><i class="fa fa-minus"></i></button>
                <button class="boton boton--danger mov-eliminar"><i class="fa-solid fa-trash"></i></button>
            </div> 
        </td>
    `;

    tablaBody().appendChild(fila);

    actualizarEstadoMov();

}

function actualizarEstadoMov(){

    App.movimientos.articulos = [];

    tablaBody().querySelectorAll('tr').forEach(fila => {  
        App.movimientos.articulos.push({

            idproducto: fila.dataset.idproducto,
            tienestock: fila.dataset.tieneStock,
            nombre: fila.querySelector('.descripcion').innerText.trim(),
            cantidad: parseFloat(fila.querySelector('.cantidad').value),
            unidad: fila.querySelector('.unidad').innerText.trim()
        });
    });
   
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

    if (!cantidad || !maxStock ) return;
    
    if(isNaN(cantidad) || cantidad < 0){
        cantidad = 0;
    }

    if(esMovimientoResta() && cantidad > maxStock){

        mostrarAlerta(
            "warning",
            "Stock máximo alcanzado",
            "No puedes ingresar una cantidad mayor al stock disponible."
        );

        cantidad = maxStock;
        cantidadInput.value = maxStock;
    } 
    actualizarEstadoMov();
}


export function resetMovimientos(){

    App.movimientos.articulos = [];
    // limpiar tabla
    const tbody = document.querySelector('#tablaArticulosMovimientos tbody');
    if(tbody) tbody.innerHTML = '';

    const buscarProducto = document.querySelector("#buscarProductoMov");  

    if(buscarProducto){
        buscarProducto.value = "";              
    }
    
    const inputObservacion = document.getElementById('observacion_movimiento');
    if(inputObservacion) inputObservacion.value = "";    

    const selectTipo = document.getElementById('idtipo');

    if(selectTipo){
        selectTipo.value = '';
    }

    const selectTienda = document.getElementById('idtienda');

    if(selectTienda){
        selectTienda.value = '';
        selectTienda.disabled =true;
    }
    cargarProductos();

}


export function cargarDetalleEdicion(detalle = []) {

    if (!Array.isArray(detalle) || detalle.length === 0) {
        return;
    }

    // Limpiar arreglo actual
    App.movimientos.articulos = [];

    detalle.forEach(item => {

        const producto = {
            id: item.idproducto,
            value: item.nombre, 
            unidad: item.unidad,            
            codigo: item.codigo || '',            
            tiene_stock: 1,
            stock_disponible: 999999
        };
  
        agregarProducto(producto);
        
        const fila = document.querySelector(
            `#tablaArticulosMovimientos tbody tr[data-idproducto="${item.idproducto}"]`
        );

        if (!fila) return;

        const inputCantidad = fila.querySelector('.cantidad');
        if (inputCantidad) {
            inputCantidad.value = parseFloat(item.cantidad);
        }
       
    });

    actualizarEstadoMov();    
}