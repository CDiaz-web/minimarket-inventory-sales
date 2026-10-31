import Swal from "sweetalert2";

export function initGestionOV() {

    manejarAprobacion();
    manejarAnulacion();
    manejarEdicion();

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

        if(estadoActual === 'SVP'){
            Swal.fire({
                icon:'error',
                title:'La Orden se encuentra con Documentos Relacionados'
            });
            return;
        }

        const idOrden = boton.dataset.id;

        // Determinar operación según el estado actual
        const nuevoEstado = estadoActual === 'APR' ? 'PEN' : 'APR';
        const esReapertura = nuevoEstado === 'PEN';

        Swal.fire({
            icon:'question',
            title: esReapertura ? '¿Reabrir orden?' : '¿Aprobar orden?',
            text: esReapertura
                ? 'La orden regresará a pendiente y se liberarán las cantidades comprometidas. ¿Desea continuar?'
                : '¿Está seguro de aprobar la orden de venta y reservar las cantidades correspondientes?',
            showCancelButton:true,
            confirmButtonText: esReapertura ? 'Sí, reabrir' : 'Sí, aprobar',
            cancelButtonText:'Cancelar'
        })
        .then(result => {

            if(!result.isConfirmed) return;

            cambiarEstado(idOrden, nuevoEstado);

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

        // if(estadoActual === 'APR'){
        //     Swal.fire({
        //         icon:'error',
        //         title:'La Orden se encuentra Aprobada, no puede ser anulada'
        //     });
        //     return;
        // }        

        if(estadoActual === 'PEN' || estadoActual === 'APR'){      

            const result = await Swal.fire({
                icon:'warning',
                title:'¿Anular orden?',
                showCancelButton:true,
                confirmButtonText:'Sí, anular',
                cancelButtonText:'Cancelar'
            });

            if(!result.isConfirmed) return;

            cambiarEstado(idOrden, 'ANU', null);

        }

    });

}

// ==============================
// valida edicion solo de oc pendientes
// ==============================

function manejarEdicion() {

    document.addEventListener('click', function(e) {

        const boton = e.target.closest('.btn-editar-venta');
        if (!boton) return;

        const estado = boton.dataset.estado;

        // Solo se pueden editar órdenes pendientes
        if (estado !== 'PEN') {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Orden no editable',
                text: 'Solo las órdenes en estado Pendiente pueden modificarse.'
            });

            return;
        }

        // Si es PEN, el enlace continúa normalmente
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