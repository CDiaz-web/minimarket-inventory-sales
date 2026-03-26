import { initListasVenta } from './ventas-listas.js';
import { initClientesVenta } from './ventas-clientes.js';
import { initVentaMoneda } from "./ventas-moneda.js";
import { initProductosVenta, cargarProductosPorLista } from './ventas-productos.js';
import { initOrdenVenta } from './ventas-orden.js';
import { initGestionOV } from "./ventas-gestion.js";
// ======================
// ESTADO DEL MODULO
// ======================

window.App = window.App || {};

App.ventas = {
    idcliente: null,
    idlista: null,
    tipoCambio: 2,
    articulos: [],
    moneda:2,
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

        initListasVenta();
        initClientesVenta();
        initProductosVenta();
        initVentaMoneda();
        initOrdenVenta();

    }

    // ======================
    // MODULO GESTION OV
    // ======================

    if(document.querySelector('.btn-aprobar') || document.querySelector('.btn-anular')){
        initGestionOV();
    }

}