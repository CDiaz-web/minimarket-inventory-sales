import './modal-loader.js';
import { events } from './events.js';
import { alert } from '../plugins/sweetalert.js';
import { http } from './http.js';
// 🌎 objeto global de la app
window.App = {
    alert,
    http
};

// ======================
// INICIALIZACIONES
// ======================

document.addEventListener('DOMContentLoaded', () => {

    initModalLoader();

    App.events = events;
    App.events.init();

    // Inicializar librerías externas
    if (window.AOS) {
        AOS.init({ once: true });
    }

    console.log('App iniciada correctamente');

});