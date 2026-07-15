import Swal from "sweetalert2";
import { modal } from "../../core/modal-manager.js";
import { resetMovimientos } from "./movimientos-articulos.js";

export function initMovimientoInventario(){   

    const btnGenerar = document.querySelector('#btngenera_mov');
    if (!btnGenerar) return;
   
    
     
    const inputObservacion = document.getElementById("observacion_movimiento");
     if(!inputObservacion) return;
    // ==============================
    // CLICK BOTON
    // ==============================

    btnGenerar.addEventListener("click", async function(e){

        e.preventDefault();    
        if(!(await validarMovimiento())) return;
        //console.log(App.movimientos);
        RegistraMovimiento();

    });     

    // ==============================
    // VALIDACIONES
    // ==============================

    async function validarMovimiento(){

        const fechaSeleccionada = document.getElementById('fecha_movimiento')?.value;

        if(!fechaSeleccionada){

            Swal.fire({
                icon:'warning',
                title:'Fecha requerida',
                text:'Seleccione una fecha'
            });

            return false;
        }else{              
            App.movimientos.fecha = fechaSeleccionada;
        }

        if(!App.movimientos.idtipo){

            Swal.fire({
                icon:'warning',
                title:'Tipo Movimiento requerido',
                text:'Seleccione un Tipo de Movimiento'
            });

            return false;
        }

        if(App.movimientos.esTransferencia === 1 && !App.movimientos.idtienda_relacion ){

            Swal.fire({
                icon:'warning',
                title:'Tienda Destino requerida',
                text:'Seleccione la tienda de destino'
            });

            return false;
        }

        if(App.movimientos.articulos.length === 0){

            Swal.fire({
                icon:'warning',
                title:'No hay artículos',
                text:'Agregue al menos un artículo'
            });

            return false;
        }      


        return true;        

    }


    async function RegistraMovimiento(){

        const payload = {

            cabecera:{

                idmovimiento: App.movimientos.idmovimiento,
                idtipo: App.movimientos.idtipo,
                fecha: App.movimientos.fecha,           
                observacion: inputObservacion.value,
                idtienda_relacion:App.movimientos.idtienda_relacion
            },

            detalle: App.movimientos.articulos

        };

        // ======================
        // DEFINIR URL
        // ======================
        const esEdicion = !!App.movimientos.idmovimiento;

        const url = esEdicion
            ? '/admin/gestion/inventarios/movimiento/editar'
            : '/admin/gestion/inventarios/movimiento/generar';

        try{

            const res = await fetch(url, {
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if(!data.ok){
                Swal.fire("Error", data.mensaje, "error");
                return;
            }

            modal.close();
            Swal.fire({
                icon: 'success',
                title: esEdicion
                    ? `Mov. ${data.numero} actualizada`
                    : `Mov. ${data.numero} generada`,
                text: '¿Desea imprimir el Movimiento?',
                showCancelButton: true,
                confirmButtonText: 'Sí, imprimir',
                cancelButtonText: 'No'
            }).then(result => {
                
                if (result.isConfirmed) {
                    window.open(
                        `/admin/gestion/inventarios/movimiento/imprimir?id=${data.idmovimiento}`,
                        '_blank'
                    );
                }
               
                resetMovimientos();
            });

        } catch(error){

            console.error(error);

            Swal.fire(
                "Error",
                esEdicion
                    ? "No se pudo actualizar el Movimiento"
                    : "No se pudo registrar el Movimiento",
                "error"
            );
        }
    }

}