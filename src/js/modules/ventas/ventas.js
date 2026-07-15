import { initListasVenta, seleccionarListaPorId } from './ventas-listas.js';
import { initClientesVenta } from './ventas-clientes.js';
import { initVentaMoneda,initFechaVenta } from "./ventas-moneda.js";
import { initProductosVenta, cargarProductosPorLista } from './ventas-productos.js';
import { initOrdenVenta } from './ventas-orden.js';
import { initGestionOV } from "./ventas-gestion.js";
import {
    initTablaVentas,
    cargarDetalleEdicion
} from './ventas-articulos.js';
// ======================
// ESTADO DEL MODULO
// ======================

window.App = window.App || {};

App.ventas = {
    idorden: null,
    idcliente: null,
    idlista: null,
    tipoCambio: 2,
    tipoCambio_oficial: 2,
    articulos: [],
    moneda:2,
    impuesto:parseFloat(window.APP?.config?.impuesto || 0),   
    totales: {
        subtotal: 0,
        impuesto: 0,
        total: 0
    }
};

// ======================
// INIT

export function initVentas(){
    const inputLista = document.getElementById('buscarLista');
    const inputCliente = document.getElementById('buscarCliente');
    const inputProductos = document.getElementById('buscarProducto'); 
  

    // ======================
    // MODULO POS / VENTAS
    // ======================

    if(inputLista && inputCliente && inputProductos){
        const inputIdOrden = document.getElementById('idorden');
        const inputIdCliente = document.getElementById('idcliente_hidden');

        initListasVenta();
        initClientesVenta();
        initProductosVenta();
        initVentaMoneda();
        initOrdenVenta();
        initFechaVenta();
        initTablaVentas();

        if (inputIdOrden?.value) {
            App.ventas.idorden = parseInt(inputIdOrden.value);
        }

        if (inputIdCliente?.value) {
            App.ventas.idcliente = parseInt(inputIdCliente.value);
        }

        // ======================
        // CARGAR ORDEN EN EDICIÓN
        // ======================

        if (window.APP?.ventaEdicion?.detalle?.length > 0) {

            // Restaurar id de orden
            App.ventas.idorden =
                parseInt(window.APP.ventaEdicion.cabecera.idorden);

            // Restaurar cliente
            App.ventas.idcliente =
                parseInt(window.APP.ventaEdicion.cabecera.idcliente);

            // Restaurar lista de precios
            App.ventas.idlista =
                parseInt(window.APP.ventaEdicion.cabecera.idlista);

            // Mostrar descripción de la lista
            const inputLista = document.getElementById('buscarLista');
            
            if (inputLista) {
                inputLista.value =
                    window.APP.ventaEdicion.cabecera.lista || '';
            }

            // Restaurar moneda
            App.ventas.moneda =
                parseInt(window.APP.ventaEdicion.cabecera.idmoneda);

            // Mostrar lista visualmente
            seleccionarListaPorId(App.ventas.idlista);

            // Cargar productos asociados a la lista
            cargarProductosPorLista(App.ventas.idlista);

            // Restaurar detalle
            cargarDetalleEdicion(
                window.APP.ventaEdicion.detalle
            );
        } 


    }

    // ======================
    // MODULO GESTION OV
    // ======================

    if(document.querySelector('.btn-aprobar') || document.querySelector('.btn-anular')){
        initGestionOV();
    }

}