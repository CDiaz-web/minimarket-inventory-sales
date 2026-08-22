import { initTables } from '../../ui/table.js';

export function initRecepcionCompra() {

    const btnBuscaOC = document.getElementById('btnbuscaOC');

    if (!btnBuscaOC) return;

    btnBuscaOC.addEventListener('click', abrirModalOC);
}


// ============================
// ABRIR MODAL
// ============================

async function abrirModalOC() {

    const modal = document.getElementById('recepcionOCModal');

    if (!modal) return;

    const table = modal.querySelector('#recepcionOCModalTable');

    if (!table) return;

    const thead = table.querySelector('thead');
    const tbody = table.querySelector('tbody');

    if (!thead || !tbody) return;

    resetModalOC(thead, tbody);

    const bsModal = bootstrap.Modal.getOrCreateInstance(modal);

    bsModal.show();

    await cargarOrdenesPorRecibir(thead, tbody);

}


// ============================
// CARGAR ORDENES
// ============================

async function cargarOrdenesPorRecibir(thead, tbody) {

    try {

        const response = await fetch(
            '/api/compras/ordenes-por-recibir'
        );

        if (!response.ok) {
            throw new Error('Error al consultar las órdenes de compra');
        }

        const data = await response.json();

        construirHeader(thead);

        construirBody(tbody, data);

        initTables();

    } catch (error) {

        console.error(
            'Error cargando órdenes por recibir:',
            error
        );

    }

}


// ============================
// HEADER
// ============================

function construirHeader(thead) {

    thead.innerHTML = '';

    const tr = document.createElement('tr');

    const columnas = [
        'Número',
        'Fecha',
        'Proveedor',
        'Estado',
        'Observación',
        'Acción'
    ];

    columnas.forEach(texto => {

        const th = document.createElement('th');

        th.textContent = texto;

        tr.appendChild(th);

    });

    thead.appendChild(tr);

}


// ============================
// BODY
// ============================

function construirBody(tbody, data) {

    tbody.innerHTML = '';

    data.forEach(row => {

        const tr = document.createElement('tr');

        tr.dataset.id = row.id;

        agregarCelda(tr, row.numero);
        agregarCelda(tr, row.fecha);
        agregarCelda(tr, row.proveedor);
        agregarCelda(tr, row.estado);
        agregarCelda(tr, row.observacion);

        const tdAccion = document.createElement('td');

        const btn = document.createElement('button');

        btn.type = 'button';
        btn.className = 'boton boton--primary';
        btn.textContent = 'Seleccionar';

        btn.addEventListener('click', () => {
            seleccionarOC(row);
        });

        tdAccion.appendChild(btn);

        tr.appendChild(tdAccion);

        tbody.appendChild(tr);

    });

}


// ============================
// CELDA
// ============================

function agregarCelda(tr, valor) {

    const td = document.createElement('td');

    td.textContent = valor ?? '';

    tr.appendChild(td);

}


// ============================
// SELECCIONAR OC
// ============================

function seleccionarOC(orden) {

    console.log('OC seleccionada:', orden);

    App.compras.idorden = parseInt(orden.id);
    App.compras.numero = orden.numero;

    const modal = document.getElementById('recepcionOCModal');

    if (modal) {

        const bsModal =
            bootstrap.Modal.getInstance(modal);

        bsModal?.hide();

    }

}


// ============================
// RESET
// ============================

function resetModalOC(thead, tbody) {

    thead.innerHTML = '';
    tbody.innerHTML = '';

    const modal =
        document.getElementById('recepcionOCModal');

    if (!modal) return;

    const search =
        modal.querySelector('[data-table-search]');

    if (search) {
        search.value = '';
    }

    const pagination =
        modal.querySelector('[data-table-pagination]');

    if (pagination) {
        pagination.innerHTML = '';
    }

}