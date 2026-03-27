import { initTables } from '../ui/table.js';

export function initCardsModal() {

    const cardModal = document.getElementById('cardModal');

    // si la página no tiene modal → salir limpio
    if (!cardModal) return;

    cardModal.addEventListener('shown.bs.modal', () => {
        initTables();
    });


    // Escucha todos los clicks en el documento
    document.addEventListener('click', async (e) => {

        const trigger = e.target.closest('[data-modal="card"]');
        if (!trigger) return;
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        // Llama a abrir el modal pasando el botón
        await abrirModal(trigger);

    });


}

/* ============================
   ABRIR MODAL
============================ */

async function abrirModal(button) {

    const modal = document.getElementById('cardModal');

    if (!modal) return;

    // 🔥 asegura que nunca muera
    if (modal.parentElement !== document.body) {
        document.body.appendChild(modal);
    }

    const modalTitle = document.getElementById('cardModalTitle');
    if (!modalTitle) return;
    const table = modal.querySelector('#cardModalTable');
    if (!table) return;
    const thead = table.querySelector('thead');
    if (!thead) return;
    const tbody = table.querySelector('tbody');
    if (!tbody) return;

    modalTitle.textContent = button.dataset.title;
    modal.dataset.endpoint = button.dataset.endpoint;
    modal.dataset.columns = button.dataset.columns;

    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    await cargarDatosModal(modal, thead, tbody);
}

/* ============================
   FETCH + RENDER
============================ */

async function cargarDatosModal(modal, thead, tbody) {

    const endpoint = modal.dataset.endpoint;
    const columns = JSON.parse(modal.dataset.columns);

    try {
        
        const resp = await fetch(endpoint);
        const data = await resp.json();

        construirHeader(thead, columns);
        construirBody(tbody, data, columns);

        // activar buscador + paginación
        //initTables();

    } catch (error) {
        console.error('Error cargando modal:', error);
    }
}

/* ============================
   HEADER DINÁMICO
============================ */

function construirHeader(thead, columns) {

    thead.innerHTML = '';

    const tr = document.createElement('tr');

    columns.forEach(col => {
        const th = document.createElement('th');
        th.textContent = col.label;
        tr.appendChild(th);
    });

    thead.appendChild(tr);
}

/* ============================
   BODY DINÁMICO
============================ */

function construirBody(tbody, data, columns) {

    tbody.innerHTML = '';

    data.forEach(row => {
        const tr = document.createElement('tr');

        columns.forEach(col => {
            const td = document.createElement('td');
            td.textContent = row[col.field] ?? '';
            tr.appendChild(td);
        });

        tbody.appendChild(tr);
    });
}
/* ============================
   RESETEO
============================ */
function resetCardModal() {

    const modal = document.getElementById('cardModal');

    if (!modal) return;

    const table = modal.querySelector('#cardModalTable');
    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');

    // limpiar tabla
    thead.innerHTML = '';
    tbody.innerHTML = '';

    // limpiar buscador
    const search = modal.querySelector('[data-table-search]');
    if (search) search.value = '';

    // limpiar paginación
    const pagination = modal.querySelector('[data-table-pagination]');
    if (pagination) pagination.innerHTML = '';

    // permitir reinicialización
    delete table.dataset.initialized;
}