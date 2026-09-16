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


    async function RegistraMovimiento() {


       /* ==========================================
           CONFIRMACION
        ========================================== */

        const confirmacion = await Swal.fire({

            icon: 'question',
            title: '¿Registrar Movimiento?',
            text: 'Se generará el movimiento de inventario correspondiente.',

            showCancelButton: true,

            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar'

        });


        if (!confirmacion.isConfirmed) {
            return;
        }


        const payload = {

            cabecera: {
                idtipo: App.movimientos.idtipo,
                fecha: App.movimientos.fecha,
                observacion: inputObservacion.value.trim() || null,
                idtienda_relacion:
                    App.movimientos.esTransferencia
                        ? App.movimientos.idtienda_relacion
                        : null
            },

            detalle: App.movimientos.articulos.map(articulo => ({
                idproducto: articulo.idproducto,
                cantidad: articulo.cantidad
            }))
        };

        const esEdicion = !!App.movimientos.idmovimiento;

        const url = esEdicion
            ? '/admin/gestion/inventarios/movimiento/editar'
            : '/admin/gestion/inventarios/movimiento/generar';

        try {

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!data.ok) {
                Swal.fire("Error", data.mensaje, "error");
                return;
            }
          

        /* ==========================================
           EXITO
        ========================================== */

        await Swal.fire({

            icon: 'success',

            title: 'Recepción registrada',

            text:
                `Movimiento ${data.numero_formateado} generado correctamente.`,

            confirmButtonText: 'Aceptar'

        });

        document.getElementById('serie_inventario').value = data.serie;
        document.getElementById('numero_inventario').value = data.solo_numero_formateado;

        document.getElementById('btngenera_mov').disabled = true;
        document.getElementById('Imprimir_mov').disabled = false;
        document.getElementById('idtipo').disabled = true;
        document.getElementById('idtipo').disabled = true;
        document.getElementById('fecha_movimiento').disabled = true;
        document.getElementById('idtienda').disabled = true;
        document.getElementById('observacion_movimiento').disabled = true;
        document.getElementById('buscarProductoMov').disabled = true;
        
        

        } catch (error) {

            console.error(error);

            Swal.fire(
                "Error",
                esEdicion
                    ? "No se pudo actualizar el Movimiento"
                    : "No se pudo registrar el Movimiento",
                "error"
            );
        }


        const btnImprimir = document.getElementById('Imprimir_mov');

        if (btnImprimir) {

            btnImprimir.addEventListener('click', function () {

                const idorden = App.compras.recepcion.idinvent ;
                
                if (!idorden) {
                    return;
                }

                window.open(
                    `/admin/gestion/inventarios/movimiento/imprimir?id=${idorden}`,
                    '_blank'
                );

            });

        }



    }

}