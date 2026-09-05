
import { initProveedoresCompra } from './compras-proveedores.js';
import { initCompraMoneda } from "./compras-moneda.js";
import { initFechaCompra } from "./compras-moneda.js";
import { initProductosCompra, cargarProductos } from './compras-productos.js';
import {
    initTablaCompras,
    cargarDetalleEdicion
} from './compras-articulos.js';
import { initOrdenCompra } from './compras-orden.js';
import { initGestionOC } from "./compras-gestion.js";
import { initCompraSerie } from "./compras-serie.js";
import { initRecepcionCompra } from './compras-recepcion.js';
// ======================
// ESTADO DEL MODULO
// ======================

App.compras = {
    idorden: null,
    numero: null,
    idproveedor: null,   
    idserie: null,
    tipoCambio: 2,
    tipoCambio_oficial: 2,
    articulos: [],
    moneda:2,
    impuesto:parseFloat(window.APP?.config?.impuesto || 0),   

    recepcion: {
        idinvent:null,
        cabecera: null,
        detalle: []
    },

    totales: {
        subtotal: 0,
        impuesto: 0,
        total: 0
    }
};

// ======================
// INIT
// ======================
export function initCompras(){
   
    const inputProveedores = document.getElementById('buscarProveedor');
    const inputProductos = document.getElementById('buscarProductoCompra'); 


    // ======================
    // MODULO POS / VENTAS
    // ======================

    if(inputProveedores && inputProductos){
        const inputIdOrden = document.getElementById('idorden');
        const inputIdProveedor = document.getElementById('idproveedor_hidden');


        initProveedoresCompra();
        initProductosCompra();
        initCompraMoneda();
        initTablaCompras();
        initFechaCompra();
        initOrdenCompra();
        initCompraSerie();

        if (inputIdOrden?.value) {
            App.compras.idorden = parseInt(inputIdOrden.value);
        }

        if (inputIdProveedor?.value) {
            App.compras.idproveedor = parseInt(inputIdProveedor.value);
        }

        if (App.compras.idproveedor) {
            cargarProductos();
        }
        // ======================
        // CARGAR ORDEN EN EDICIÓN
        // ======================

        if (window.APP?.compraEdicion?.detalle?.length > 0) {

            // Restaurar proveedor seleccionado
            App.compras.idproveedor =
                window.APP.compraEdicion.cabecera.idproveedor;

            // Restaurar moneda seleccionada
            App.compras.moneda =
                window.APP.compraEdicion.cabecera.idmoneda;

            // Restaurar detalle
            cargarDetalleEdicion(
                window.APP.compraEdicion.detalle
            );
        }        

    }

    // ======================
    // MODULO GESTION OC
    // ======================

    if(document.querySelector('.btn-aprobar-compra') || document.querySelector('.btn-anular-compra')){
        initGestionOC();
    }
    // ======================
    // MODULO RECEPCION OC
    // ======================

    if (document.getElementById('btnbuscaOC')) {
        initRecepcionCompra();
    }
}
