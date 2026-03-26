

window.App = window.App || {};
// ==========================
// IMPORTS (SIEMPRE ARRIBA)
// ==========================

import { initModalLoader } from './core/modal-loader.js';
import { events } from './core/events.js';
import { iniciarAlertas } from './ui/alertas.js';
import { initTables } from './ui/table.js';
import { iniciarSidebar } from './ui/sidebar.js';
import { initDashboardChart, destroyDashboardChart } from './admin/dashboard-chart.js';
import { initCardsModal } from './admin/cards-modal.js';
import { initUsuariosTiendas } from './modules/usuarios-tiendas.js';
import { initListaProductos } from './modules/lista-productos.js';
import { initClientesForm } from "./modules/clientes/clientes-form.js";
import { initTipoCambio } from "./modules/tipo-cambio/tipo-cambio.js";
import { initVentas } from "./modules/ventas/ventas.js";
import { initInventarios } from "./modules/inventarios/movimientos.js";
import { initImprimeInventario } from "./modules/inventarios/inventario-imprimir.js";
import { initProductosTienda } from "./modules/tiendas/productos-tienda.js";
// ==========================
// APP BOOTSTRAP
// ==========================

document.addEventListener('DOMContentLoaded', () => {

    initModalLoader();
    iniciarAlertas();
    initTables();
    iniciarSidebar();
    initDashboardChart(); 
    initCardsModal();
    initUsuariosTiendas();
    initListaProductos();
    initClientesForm();
    initTipoCambio();
    initVentas(); 
    initInventarios();
    initImprimeInventario();
    initProductosTienda();
    App.events = events;
    App.events.init();

});

