import { initTables } from '../../ui/table.js';

export function initRecepcionCompra() {

    const btnBuscaOC = document.getElementById('btnbuscaOC');

    if (!btnBuscaOC) return;

    btnBuscaOC.addEventListener('click', abrirModalOC);

    initValidacionCantidadRecepcion();

    const btnGuardar =
        document.getElementById('btnGuardarRecepcion');

    if (btnGuardar) {

        btnGuardar.addEventListener(
            'click',
            guardarRecepcion
        );

    }    
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

async function seleccionarOC(orden) {

    try {

        const idorden = parseInt(orden.id);

        if (!idorden) {
            throw new Error('Orden de compra no válida.');
        }

        const response = await fetch(
            `/api/compras/obtenerRecepcion?id=${idorden}`
        );

        if (!response.ok) {
            throw new Error(
                'No se pudo obtener la orden de compra.'
            );
        }

        const data = await response.json();

        if (!data.cabecera) {
            throw new Error(
                'No se encontró la información de la orden.'
            );
        }

        // ==========================
        // ESTADO
        // ==========================

        App.compras.idorden = idorden;
        App.compras.numero = data.cabecera.numero;

        App.compras.recepcion = {
            cabecera: data.cabecera,
            detalle: data.detalle || []
        };

        // ==========================
        // CARGAR PANTALLA
        // ==========================

        cargarCabeceraRecepcion(
            data.cabecera
        );

        cargarDetalleRecepcion(
            data.detalle
        );

        // ==========================
        // CERRAR MODAL
        // ==========================

        const modal =
            document.getElementById('recepcionOCModal');

        if (modal) {

            const bsModal =
                bootstrap.Modal.getInstance(modal);

            bsModal?.hide();
        }

    } catch (error) {

        console.error(
            'Error cargando la orden para recepción:',
            error
        );

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message
        });
    }
}

function cargarCabeceraRecepcion(cabecera) {

    if (!cabecera) return;

    const proveedor =
        document.getElementById('proveedor');

    const numero =
        document.getElementById('numerooc');

    const fecha =
        document.getElementById('fecha_compra');

    const estado =
        document.getElementById('estado');

    if (proveedor) {
        proveedor.value = cabecera.proveedor ?? '';
    }

    if (numero) {
        numero.value = cabecera.numero ?? '';
    }

    if (fecha) {
        fecha.value = cabecera.fecha ?? '';
    }

    if (estado) {
        estado.value = cabecera.estado ?? '';
    }
}

function cargarDetalleRecepcion(detalle = []) {

    const tbody = document.querySelector(
        '#tablaArticulosRecepcion tbody'
    );

    if (!tbody) return;

    tbody.innerHTML = '';

    detalle.forEach(item => {

        const tr = document.createElement('tr');

        tr.dataset.iddetalle = item.iddetalle;
        tr.dataset.idarticulo = item.idarticulo;
        tr.dataset.porrecibir = item.porrecibir;

        tr.innerHTML = `
            <td>
                ${item.nombre ?? ''}
            </td>

            <td>
                ${parseFloat(item.cantidad).toFixed(2)}
            </td>

            <td>
                ${parseFloat(item.cantidad_recibida).toFixed(2)}
            </td>

            <td>
                ${parseFloat(item.porrecibir).toFixed(2)}
            </td>

            <td>
                <input
                    type="number"
                    class="formulario__input cantidad-recepcion"
                    value="${parseFloat(item.arecibir)}"
                    min="0"
                    max="${parseFloat(item.porrecibir)}"
                    step="any"
                >
            </td>
        `;

        tbody.appendChild(tr);
    });
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

function initValidacionCantidadRecepcion() {

    const tabla = document.getElementById(
        'tablaArticulosRecepcion'
    );

    if (!tabla) return;

    tabla.addEventListener('input', e => {

        const input = e.target.closest(
            '.cantidad-recepcion'
        );

        if (!input) return;

        const fila = input.closest('tr');

        if (!fila) return;

        const iddetalle =
            parseInt(fila.dataset.iddetalle);

        const maximo = parseFloat(
            fila.dataset.porrecibir || 0
        );

        let valor = parseFloat(input.value);

        if (isNaN(valor)) {
            valor = 0;
        }

        if (valor < 0) {
            valor = 0;
        }

        if (valor > maximo) {
            valor = maximo;
        }

        input.value = valor;

        // ==========================
        // ACTUALIZAR ESTADO
        // ==========================

        const item =
            App.compras.recepcion.detalle.find(
                item =>
                    parseInt(item.iddetalle) === iddetalle
            );

        if (item) {
            item.arecibir = valor;
        }
    });
}

function validarRecepcion() {

    const detalle =
        App.compras.recepcion?.detalle || [];

    if (!detalle.length) {
        return false;
    }

    for (const item of detalle) {

        const porrecibir =
            parseFloat(item.porrecibir || 0);

        const arecibir =
            parseFloat(item.arecibir || 0);

        if (arecibir < 0 || arecibir > porrecibir) {

            Swal.fire({
                icon: 'warning',
                title: 'Cantidad inválida',
                text:
                    `La cantidad a recibir de "${item.nombre}" ` +
                    `no puede ser mayor a la cantidad pendiente.`
            });

            return false;
        }
    }

    return true;
}

/* =========================================================
   GUARDAR RECEPCION
========================================================= */

async function guardarRecepcion() {

    try {

        /* ==========================================
           VALIDAR ORDEN
        ========================================== */

        if (!App.compras.idorden) {

            Swal.fire({
                icon: 'warning',
                title: 'Orden no seleccionada',
                text: 'Debe seleccionar una orden de compra.'
            });

            return;
        }


        // ==========================================
        // VALIDAR RECEPCIÓN
        // ==========================================

        if (!validarRecepcion()) {
            return;
        }


        // ==========================================
        // CONSTRUIR DETALLE
        // ==========================================

        const detalle = App.compras.recepcion.detalle
            .filter(item => parseFloat(item.arecibir || 0) > 0)
            .map(item => ({

                iddetalle: parseInt(item.iddetalle),

                idproducto: parseInt(item.idarticulo),

                cantidad: parseFloat(item.arecibir)

            }));


        // ==========================================
        // VALIDAR DETALLE
        // ==========================================

        if (!detalle.length) {

            Swal.fire({
                icon: 'warning',
                title: 'Sin cantidades',
                text: 'Debe indicar al menos una cantidad a recibir.'
            });

            return;
        }      


       /* ==========================================
           CONFIRMACION
        ========================================== */

        const confirmacion = await Swal.fire({

            icon: 'question',
            title: '¿Registrar recepción?',
            text: 'Se generará el movimiento de inventario correspondiente.',

            showCancelButton: true,

            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar'

        });


        if (!confirmacion.isConfirmed) {
            return;
        }


        /* ==========================================
           CONSTRUIR JSON
        ========================================== */

        const data = {

            cabecera: {

                idorden: App.compras.idorden,

                fecha:
                    document.getElementById('fecha_compra')?.value
                    || new Date().toISOString().slice(0, 10),

                observacion:
                    document.getElementById('observacion')?.value
                    || '',

                idserie:
                    parseInt(
                        document.getElementById('idserie')?.value
                    ) || 0,

                idtipo_doc_referencial:
                    parseInt(
                        document.getElementById(
                            'idtipo_doc_referencial'
                        )?.value
                    ) || null,

                numero_doc_referencial:
                    document.getElementById(
                        'numero_doc_referencial'
                    )?.value || null,

                fecha_doc_referencial:
                    document.getElementById(
                        'fecha_doc_referencial'
                    )?.value || null,

                guia_referencial:
                    document.getElementById(
                        'guia_referencial'
                    )?.value || null,

                fecha_guia_referencial:
                    document.getElementById(
                        'fecha_guia_referencial'
                    )?.value || null

            },

            detalle: detalle
        };


        console.log(
            'JSON recepción:',
            data
        );


        /* ==========================================
           ENVIAR AL API
        ========================================== */

        const response = await fetch(
            '/api/compras/generarRecepcion',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify(data)
            }
        );


        const resultado = await response.json();


        /* ==========================================
           ERROR DEL API
        ========================================== */

        if (!response.ok || !resultado.ok) {

            throw new Error(
                resultado.mensaje
                || 'No se pudo registrar la recepción.'
            );
        }


        /* ==========================================
           EXITO
        ========================================== */

        await Swal.fire({

            icon: 'success',

            title: 'Recepción registrada',

            text:
                `Movimiento ${resultado.numero_formateado} generado correctamente.`,

            confirmButtonText: 'Aceptar'

        });


        console.log(
            'Movimiento generado:',
            resultado
        );


        /* ==========================================
           SIGUIENTE PASO
        ========================================== */

        // Aquí posteriormente podemos:
        // - limpiar la pantalla
        // - volver a Gestión de Compras
        // - imprimir el movimiento
        // - mostrar el documento generado

    } catch (error) {

        console.error(
            'Error registrando recepción:',
            error
        );

        Swal.fire({

            icon: 'error',

            title: 'Error',

            text: error.message
                || 'Ocurrió un error al registrar la recepción.'

        });

    }

}