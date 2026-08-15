import Swal from "sweetalert2";

export function initGestionOC() {

    manejarAprobacion();
    manejarAnulacion(); 
    manejarReabrir();
    manejarEdicion();
}

// ==============================
// APROBAR ORDEN
// ==============================

function manejarAprobacion(){

    document.addEventListener('click', function(e){

        const boton = e.target.closest('.btn-aprobar-compra');
        if(!boton) return;
        const estadoActual = boton.dataset.estado;
        if(estadoActual === 'ANU'){
            Swal.fire({
                icon:'error',
                title:'La Orden se encuentra anulada'
            });
            return;
        }

        if(estadoActual === 'APR'){
            Swal.fire({
                icon:'error',
                title:'La Orden ya está aprobada'
            });
            return;
        }

        if(estadoActual === 'REC'){
            Swal.fire({
                icon:'error',
                title:'La Orden se encuentra Recepcionada'
            });
            return;
        }        
        if(estadoActual === 'RCP'){
            Swal.fire({
                icon:'error',
                title:'La Orden cuenta con Ingresos Parciales'
            });
            return;
        }        
        const idOrden = boton.dataset.id;
   
        Swal.fire({
            icon:'question',
            title:'¿Aprobar orden?',
            text:'Se cambiara el estado de la Orden de Compra a Aprobado',
            showCancelButton:true,
            confirmButtonText:'Sí, aprobar',
            cancelButtonText:'Cancelar'
        })
        .then(result => {

            if(!result.isConfirmed) return;

            cambiarEstado(idOrden, 'APR');

        });

    });

}


// ==============================
// ANULAR ORDEN
// ==============================

function manejarAnulacion(){

    document.addEventListener('click', async function(e){

        const boton = e.target.closest('.btn-anular-compra');
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
            Swal.fire({
                icon:'error',
                title:'La Orden esta Aprobada, es necesario reabrir Orden'
            });
            return;
        }
        if(estadoActual === 'REC'){
            Swal.fire({
                icon:'error',
                title:'La Orden se encuentra Recepcionada'
            });
            return;
        }        
        if(estadoActual === 'RCP'){
            Swal.fire({
                icon:'error',
                title:'La Orden cuenta con Ingresos Parciales'
            });
            return;
        }  

        if(estadoActual === 'PEN'){

            try{

            const result = await Swal.fire({
                icon:'warning',
                title:'¿Anular orden?',
                showCancelButton:true,
                confirmButtonText:'Sí, anular',
                cancelButtonText:'Cancelar'
            });

            if(!result.isConfirmed) return;

            cambiarEstado(idOrden, 'ANU');

            }catch(error){

                Swal.fire(
                    'Error',
                    'Error al anular Orden',
                    'error'
                );

            }

        }

    });

}


// ==============================
// ANULAR ORDEN
// ==============================

function manejarReabrir(){

    document.addEventListener('click', async function(e){

        const boton = e.target.closest('.btn-aprobar-compra');
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

            const result = await Swal.fire({
                icon:'warning',
                title:'¿Reabrir orden?',
                showCancelButton:true,
                confirmButtonText:'Sí, Reabrir',
                cancelButtonText:'Cancelar'
            });

            if(!result.isConfirmed) return;

            cambiarEstado(idOrden, 'PEN');

            }catch(error){

                Swal.fire(
                    'Error',
                    'Error al reabrir Orden',
                    'error'
                );

            }

        }

    });

}

// ==============================
// valida edicion solo de oc pendientes
// ==============================

function manejarEdicion() {

    document.addEventListener('click', function(e) {

        const boton = e.target.closest('.btn-editar-compra');
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

   
    });

}


// ==============================
// CAMBIAR ESTADO
// ==============================

async function cambiarEstado(idOrden, estado){

    try{

        const response = await fetch('/admin/gestion/compras/gestion/cambiarestado', {
            method:'POST',
            headers:{
                'Content-Type':'application/json'
            },
            credentials:'same-origin',
            body:JSON.stringify({
                id:idOrden,
                estado:estado
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