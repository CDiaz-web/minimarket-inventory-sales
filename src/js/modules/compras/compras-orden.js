import Swal from "sweetalert2";
import { verificarTipoCambio } from "./compras-moneda.js";
import { modal } from "../../core/modal-manager.js";
import { resetCompras } from "./compras-articulos.js";


export function initOrdenCompra(){   

    const btnGenerar = document.querySelector('#btngenerarOC');
    const btnLimpiar = document.querySelector('#LimpiarOC');
    const inputObservacion = document.getElementById("observacion_compra");
    
    if (!btnGenerar) return;
    if (!btnLimpiar) return;
    if(!inputObservacion) return;
    
    // ==============================
    // CLICK BOTON
    // ==============================

    btnGenerar.addEventListener("click", async function(e){

        e.preventDefault();
    
        if(!(await validarCompra())) return;     
        
        Swal.fire({
            icon: 'warning',
            title: 'Gurdar Orden de Compra',
            text: 'Se Guardaran los Cambios realizados',
            showCancelButton: true,
            confirmButtonText: 'Sí, Guardar',
            cancelButtonText: 'Cancelar'
        }).then((result)=>{

            if(result.isConfirmed){
                RegistraCompra();
            }
        });  

    });  

    btnLimpiar.addEventListener("click", async function(e){

        e.preventDefault();
    
            // if(App.compras.articulos.length === 0) return;
            $("#buscarProducto").autocomplete("close"); 
            document.activeElement.blur(); 
            Swal.fire({
                icon: 'warning',
                title: 'Limpiar orden',
                text: 'Se eliminarán todos los artículos de la orden.',
                showCancelButton: true,
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            }).then((result)=>{

                if(result.isConfirmed){
                    resetCompras();
                }

            });

    });  

    // ==============================
    // VALIDACIONES
    // ==============================

    async function validarCompra(){

        const fechaSeleccionada = document.getElementById('fecha_compra')?.value;

        if(!fechaSeleccionada){

            Swal.fire({
                icon:'warning',
                title:'Fecha requerida',
                text:'Seleccione una fecha'
            });

            return false;
        }

        if(!App.compras.idproveedor){

            Swal.fire({
                icon:'warning',
                title:'Proveedor requerido',
                text:'Seleccione un Proveedor'
            });

            return false;
        }

        if(!App.compras.idserie){

            Swal.fire({
                icon:'warning',
                title:'Serie requerida',
                text:'Seleccione una Serie'
            });

            return false;
        }

        if(App.compras.articulos.length === 0){

            Swal.fire({
                icon:'warning',
                title:'No hay artículos',
                text:'Agregue al menos un artículo'
            });

            return false;
        }

     
        // ==============================
        // VALIDAR TIPO DE CAMBIO
        // ==============================

        const monedaBase = window.APP.config.moneda_base; 
        const validarTC = window.APP.config.validar_tc;      
       

        if(validarTC == 1 ){
            
            const existeTC = await verificarTipoCambio(fechaSeleccionada);

            if(!existeTC.success){

                Swal.fire({
                    icon:'warning',
                    title:'Tipo de cambio requerido',
                    text:'Debe registrar el tipo de cambio para la fecha seleccionada'
                });

                return false;
            }

            App.compras.tipoCambio = parseFloat(existeTC.tc);

        }

        return true;        

    }



    async function RegistraCompra(){

        const payload = {

            cabecera:{

                idorden: App.compras.idorden,
                fecha: document.getElementById('fecha_compra')?.value,
                idproveedor: App.compras.idproveedor,
                observacion: inputObservacion.value,
                idmoneda: App.compras.moneda,
                idserie: App.compras.idserie,
                porcentaje_impuesto: App.compras.impuesto,
                subtotal: parseFloat(App.compras.totales.subtotal),
                igv: parseFloat(App.compras.totales.impuesto),
                total: parseFloat(App.compras.totales.total),
                tipocambio: App.compras.tipoCambio,
                tipocambio_oficial: App.compras.tipoCambio_oficial

            },

            detalle: App.compras.articulos

        };

        // ======================
        // DEFINIR URL
        // ======================
        const esEdicion = !!App.compras.idorden;

        const url = esEdicion
            ? '/admin/gestion/compras/orden/editar'
            : '/admin/gestion/compras/orden/generar';

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
                    ? `OC ${data.numero} actualizada`
                    : `OC ${data.numero} generada`,
                text: 'La Orden de Compra se registró correctamente.',
                confirmButtonText: 'Aceptar'
            }).then(() => {

                // Guardamos la orden actualmente registrada
                App.compras.idorden = data.idorden;
                App.compras.numero = data.numero_formateado;
                
                const numeroOrden = document.getElementById('numero');

                if (numeroOrden) {
                    numeroOrden.value = data.numero_formateado;               
                }

                const btnImprimir = document.getElementById('ImprimirOC');

                if (btnImprimir) {
                    btnImprimir.disabled = false;
                }

            });

        } catch(error){

            console.error(error);

            Swal.fire(
                "Error",
                esEdicion
                    ? "No se pudo actualizar la orden"
                    : "No se pudo registrar la orden",
                "error"
            );
        }
    }

    const btnImprimir = document.getElementById('ImprimirOC');

    if (btnImprimir) {

        btnImprimir.addEventListener('click', function () {

            const idorden = App.compras.idorden;

            if (!idorden) {
                return;
            }

            window.open(
                `/admin/gestion/compras/orden/imprimir?id=${idorden}`,
                '_blank'
            );

        });

    }


}