document.addEventListener('DOMContentLoaded', function () {

    inicializarEventos();

});

function inicializarEventos() {
    manejarAprobacion();
    manejarAnulacion();
   
}

function manejarAprobacion() {

    document.addEventListener('click', function (e) {

        if (!e.target.closest('.btn-aprobar')) return;

        const boton = e.target.closest('.btn-aprobar');
        const idOrden = boton.dataset.id;

        Swal.fire({
            icon: 'question',
            title: '¿Aprobar orden?',
            text: 'Se descontará el stock y se generará movimiento de inventario.',
            showCancelButton: true,
            confirmButtonText: 'Sí, aprobar',
            cancelButtonText: 'Cancelar'
        }).then(result => {

            if (!result.isConfirmed) return;

            cambiarEstado(idOrden, 11); // 11 = aprobado
        });

    });
}



function manejarAnulacion() {

    document.addEventListener('click', async function (e) {

        const btn = e.target.closest('.btn-anular');
        if (!btn) return;

        const idOrden = btn.dataset.id;
        const estadoActual = btn.dataset.estado;

        if (estadoActual === 'APR') {

            try {

                const response = await fetch('/admin/configuracion/devolucion/listar');
                const motivos = await response.json();
            
                const result = await Swal.fire({
                    title: 'Motivo de devolución',
                    input: 'select',
                    inputOptions: motivos,
                    inputPlaceholder: 'Seleccione un motivo',
                    showCancelButton: true,
                    confirmButtonText: 'Anular',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Debe seleccionar un motivo';
                        }
                    }
                });

                if (!result.isConfirmed) return;

                cambiarEstado(idOrden, 2, result.value);

            } catch (error) {

                Swal.fire(
                    'Error',
                    'No se pudieron cargar los motivos',
                    'error'
                );

            }

        } else {

            const result = await Swal.fire({
                icon: 'warning',
                title: '¿Anular orden?',
                showCancelButton: true,
                confirmButtonText: 'Sí, anular',
                cancelButtonText: 'Cancelar'
            });

            if (!result.isConfirmed) return;

            cambiarEstado(idOrden, 2, null);
        }

    });
}



function cambiarEstado(idOrden, estado, idMotivo = null) {

    fetch('/admin/gestion/ventas/gestion/cambiarestado', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        credentials: 'same-origin',
        body: JSON.stringify({
            id: idOrden,
            estado: estado,
            idmotivo: idMotivo
        })
    })
    .then(response => response.json())
    .then(data => {

        if (data.ok) {

            Swal.fire({
                icon: 'success',
                title: 'Estado actualizado'
            }).then(() => {
                location.reload();
            });

        } else {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.mensaje || 'No se pudo cambiar el estado'
            });
        }

    })
    .catch(error => {

        console.error('Error:', error);

        Swal.fire({
            icon: 'error',
            title: 'Error inesperado'
        });

    });
}

