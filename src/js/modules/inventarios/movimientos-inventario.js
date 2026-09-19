import Swal from "sweetalert2";
import { modal } from "../../core/modal-manager.js";
import { resetMovimientos } from "./movimientos-articulos.js";
import { FALSE } from "sass";

export function initMovimientoInventario(){   

    const btnGenerar = document.querySelector('#btngenera_mov');
    const btnLimpiar = document.querySelector('#btnLimpiarMov');
    if (!btnGenerar) return;
    if (!btnLimpiar) return; 
    
     
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

    btnLimpiar.addEventListener("click", async function(e){

        e.preventDefault();
    
            // if(App.compras.articulos.length === 0) return;
            $("#buscarProducto").autocomplete("close"); 
            document.activeElement.blur(); 
            Swal.fire({
                icon: 'warning',
                title: 'Limpiar Movimiento',
                text: 'Se eliminarán todos los artículos del Movimiento',
                showCancelButton: true,
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            }).then((result)=>{

                if (result.isConfirmed) {

                    // ==========================================
                    // LIMPIAR ESTADO DE LA RECEPCIÓN
                    // ==========================================

                    App.compras.idmovimiento = null;
                    App.compras.idtipo = null;
                    App.compras.idtienda_relacion = 0;
                    App.compras.fecha = null;
                    App.compras.observacion = '';
                    App.compras.accion = null;
                    App.compras.esTransferencia = 0;
                    
                    // ==========================================
                    // LIMPIAR DETALLE
                    // ==========================================

                    const tbody =
                        document.querySelector('#tablaArticulosMovimientos tbody');

                    if (tbody) {
                        tbody.innerHTML = '';
                    }

                    // ==========================================
                    // SERIE
                    // ==========================================   
                    const serie =
                        document.getElementById('serie_inventario');
                    if (serie) {
                        serie.value = '----';
                    }
   
                    // ==========================================
                    // NUMERO
                    // ==========================================

                    const numeroRecepcion =
                        document.getElementById('numero_inventario');

                    if (numeroRecepcion) {
                        numeroRecepcion.value = '(Automático)';
                    }
                    // ==========================================
                    // TIPO MOVIMIENTO
                    // ==========================================

                    const tipoMovimiento =
                        document.getElementById('idtipo');

                    if (tipoMovimiento) {
                        tipoMovimiento.value = '';
                    }
                    // ==========================================
                    // RESTAURAR FECHA ACTUAL
                    // ==========================================

                    const fechaInventario =
                        document.getElementById('fecha_movimiento');

                    if (fechaInventario) {
                        fechaInventario.value = obtenerFechaActual();
                    }

                    // ==========================================
                    // TIENDA DESTINO
                    // ==========================================

                    const tiendaDestino =
                        document.getElementById('idtienda');

                    if (tiendaDestino) {
                        tiendaDestino.value = '';
                    }
                    // ==========================================
                    // OBSERVACIÓN
                    // ==========================================

                    const observacion =
                        document.getElementById('observacion_movimiento');

                    if (observacion) {
                        observacion.value = '';
                    }
                  

                    // ==========================================
                    // BOTONES
                    // ==========================================

                    const btnGuardar = document.getElementById('btngenera_mov');

                    if (btnGuardar) {
                        btnGuardar.disabled = false;
                    }

                    const btnImprimir = document.getElementById('Imprimir_mov');

                    if (btnImprimir) {
                        btnImprimir.disabled = true;
                    }
                    document.getElementById('idtipo').disabled = false;  
                    document.getElementById('fecha_movimiento').disabled = false;
                    document.getElementById('observacion_movimiento').disabled = false;
                    document.getElementById('buscarProductoMov').disabled = false;
                }

            });

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
        document.getElementById('fecha_movimiento').disabled = true;
        document.getElementById('idtienda').disabled = true;
        document.getElementById('observacion_movimiento').disabled = true;
        document.getElementById('buscarProductoMov').disabled = true;
        
        App.movimientos.idmovimiento  = data.idmovimiento;    
        

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

                const idmovimiento = App.movimientos.idmovimiento ;
                
                if (!idmovimiento) {
                    return;
                }

                window.open(
                    `/admin/gestion/inventarios/movimiento/imprimir?id=${idmovimiento}`,
                    '_blank'
                );

            });

        }



    }

}

function obtenerFechaActual() {

    const hoy = new Date();

    const anio = hoy.getFullYear();
    const mes = String(hoy.getMonth() + 1).padStart(2, '0');
    const dia = String(hoy.getDate()).padStart(2, '0');

    return `${anio}-${mes}-${dia}`;
}