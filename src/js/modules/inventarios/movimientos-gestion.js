import Swal from "sweetalert2";

export function initInventariosMov() {    
    manejarAnulacion();   

}


// ==============================
// ANULAR ORDEN
// ==============================

function manejarAnulacion(){

    document.addEventListener('click', async function(e){

        const boton = e.target.closest('.btn-anular-mov');
        if(!boton) return;

        const idMovimiento = boton.dataset.id;
        const estadoActual = boton.dataset.estado;
        const esGenerado = boton.dataset.es_generado;

        if(estadoActual === 'ANU'){
            Swal.fire({
                icon:'error',
                title:'El Movimiento se encuentra anulado'
            });
            return;
        }
        if(esGenerado === '1'){
            Swal.fire({
                icon:'error',
                title:'Movimiento automatico, no puede ser anulado'
            });
            return;
        }
        if(estadoActual === 'ACT'){

            try{                

                const result = await Swal.fire({
                    title:'Motivo de devolución',
                    input:'text',
                    inputPlaceholder:'Ingrese motivo de anulacion',
                    showCancelButton:true,
                    confirmButtonText:'Anular',
                    cancelButtonText:'Cancelar',
                    inputValidator:(value)=>{
                        if(!value){
                            return 'Debe ingresar motivo de anulación';
                        }
                    }
                });

                if(!result.isConfirmed) return;
               
                anulaMovimiento(idMovimiento,  result.value);

            }catch(error){

                Swal.fire(
                    'Error',
                    'No se pudieron cargar los motivos',
                    'error'
                );

            }

        }

    });

}



async function anulaMovimiento(idMovimiento,  Motivo = null){

    try{

        const response = await fetch('/admin/gestion/inventarios/gestion/anularmovimiento', {
            method:'POST',
            headers:{
                'Content-Type':'application/json'
            },
            credentials:'same-origin',
            body:JSON.stringify({
                id:idMovimiento,                
                motivo_anulacion:Motivo
            })
        });

        const data = await response.json();

        if(data.ok){

            Swal.fire({
                icon:'success',
                title:'Movimiento anulado'
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