import Swal from "sweetalert2";
import { verificarTipoCambio } from "./compras-moneda.js";
import { modal } from "../../core/modal-manager.js";
import { resetCompras } from "./compras-articulos.js";


export function initOrdenCompra(){   

    const btnGenerar = document.querySelector('#btngenerarOC');
    const btnLimpiar = document.querySelector('#LimpiarOC');
    if (!btnGenerar) return;
    if (!btnLimpiar) return;
     
    const inputObservacion = document.getElementById("observacion_compra");
     if(!inputObservacion) return;
    // ==============================
    // CLICK BOTON
    // ==============================

    btnGenerar.addEventListener("click", async function(e){

        e.preventDefault();
    
        if(!(await validarCompra())) return;
    
        RegistraCompra();

    });  

    btnLimpiar.addEventListener("click", async function(e){

        e.preventDefault();
    
            if(App.compras.articulos.length === 0) return;
            $("#buscarProducto").autocomplete("close"); // cerrar autocomplete
            document.activeElement.blur(); // 👈 quitar focus
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
                text: '¿Desea imprimir la orden de Compra?',
                showCancelButton: true,
                confirmButtonText: 'Sí, imprimir',
                cancelButtonText: 'No'
            }).then(result => {

                if (result.isConfirmed) {
                    window.open(
                        `/admin/gestion/compras/orden/imprimir?id=${data.idorden}`,
                        '_blank'
                    );
                }

                resetCompras();
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

}