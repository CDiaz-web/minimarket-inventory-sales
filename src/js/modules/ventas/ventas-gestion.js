import Swal from "sweetalert2";

export function initGestionOV() {

    manejarAprobacion();
    manejarAnulacion();

}


// ==============================
// APROBAR ORDEN
// ==============================

function manejarAprobacion(){

    document.addEventListener('click', function(e){

        const boton = e.target.closest('.btn-aprobar');
        if(!boton) return;
        const estadoActual = boton.dataset.estado;
        if(estadoActual === 'ANU'){
            Swal.fire({
                icon:'error',
                title:'La Orden se encuentra anulada'
            });
            return;
        }
        const idOrden = boton.dataset.id;
   
        Swal.fire({
            icon:'question',
            title:'¿Aprobar orden?',
            text:'Se descontará el stock y se generará movimiento de inventario.',
            showCancelButton:true,
            confirmButtonText:'Sí, aprobar',
            cancelButtonText:'Cancelar'
        })
        .then(result => {

            if(!result.isConfirmed) return;

            cambiarEstado(idOrden, 11);

        });

    });

}


// ==============================
// ANULAR ORDEN
// ==============================

function manejarAnulacion(){

    document.addEventListener('click', async function(e){

        const boton = e.target.closest('.btn-anular');
        if(!boton) return;

        const idOrden = boton.dataset.id;
        const estadoActual = boton.dataset.estado;

        if(estadoActual === 'ANU'){
            Swal.fire({
                icon:'error',
                title:'La Orden se encuentra anulada'
            });
            return;
        }

        if(estadoActual === 'APR'){

            try{

                const response = await fetch('/admin/configuracion/devolucion/listar');
                const motivos = await response.json();

                const result = await Swal.fire({
                    title:'Motivo de devolución',
                    input:'select',
                    inputOptions:motivos,
                    inputPlaceholder:'Seleccione un motivo',
                    showCancelButton:true,
                    confirmButtonText:'Anular',
                    cancelButtonText:'Cancelar',
                    inputValidator:(value)=>{
                        if(!value){
                            return 'Debe seleccionar un motivo';
                        }
                    }
                });

                if(!result.isConfirmed) return;

                cambiarEstado(idOrden, 2, result.value);

            }catch(error){

                Swal.fire(
                    'Error',
                    'No se pudieron cargar los motivos',
                    'error'
                );

            }

        }else{

            const result = await Swal.fire({
                icon:'warning',
                title:'¿Anular orden?',
                showCancelButton:true,
                confirmButtonText:'Sí, anular',
                cancelButtonText:'Cancelar'
            });

            if(!result.isConfirmed) return;

            cambiarEstado(idOrden, 2, null);

        }

    });

}


// ==============================
// CAMBIAR ESTADO
// ==============================

async function cambiarEstado(idOrden, estado, idMotivo = null){

    try{

        const response = await fetch('/admin/gestion/ventas/gestion/cambiarestado', {
            method:'POST',
            headers:{
                'Content-Type':'application/json'
            },
            credentials:'same-origin',
            body:JSON.stringify({
                id:idOrden,
                estado:estado,
                idmotivo:idMotivo
            })
        });

        const data = await response.json();

        if(data.ok){

            Swal.fire({
                icon:'success',
                title:'Estado actualizado'
            }).then(()=>{
                location.reload();
            });

        }else{

            Swal.fire({
                icon:'error',
                title:'Error',
                text:data.mensaje || 'No se pudo cambiar el estado'
            });

        }

    }catch(error){

        console.error(error);

        Swal.fire({
            icon:'error',
            title:'Error inesperado'
        });

    }

}