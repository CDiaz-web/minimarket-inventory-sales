import Swal from "sweetalert2";
import { verificarTipoCambio } from "./ventas-moneda.js";
import { modal } from "../../core/modal-manager.js";
import { resetVenta } from "./ventas-articulos.js";
export function initOrdenVenta(){

    const btnGenerar = document.querySelector('#btngenerar');
    if (!btnGenerar) return; 

    // ==============================
    // VALIDACIONES
    // ==============================

    async function validarVenta(){


        const fechaSeleccionada = document.getElementById('fecha_venta')?.value;

        const selectFormaPago = document.getElementById('idtipopago');

        const formaPago = selectFormaPago?.value;

        if(!fechaSeleccionada){

            Swal.fire({
                icon:'warning',
                title:'Fecha requerida',
                text:'Seleccione una fecha'
            });

            return false;
        }

        if(!formaPago){

            Swal.fire({
                icon:'warning',
                title:'Forma de Pago Requerido',
                text:'Seleccione Forma de Pago'
            });

            return false;
        }


        if(!App.ventas.idcliente){

            Swal.fire({
                icon:'warning',
                title:'Cliente requerido',
                text:'Seleccione un cliente'
            });

            return false;
        }

        if(!App.ventas.idlista){

            Swal.fire({
                icon:'warning',
                title:'Lista requerida',
                text:'Seleccione una lista de precios'
            });

            return false;
        }

        if(App.ventas.articulos.length === 0){

            Swal.fire({
                icon:'warning',
                title:'No hay artículos',
                text:'Agregue al menos un artículo'
            });

            return false;
        }

        // ==============================
        // VALIDAR STOCK
        // ==============================

        const articulosSinStock = App.ventas.articulos.filter(a => 
            a.tienestock == 0
        );
      
        if(articulosSinStock.length > 0){

            Swal.fire({
                icon:'warning',
                title:'Stock insuficiente',
                text:'Existen artículos sin stock disponible. Revise la cantidad o elimínelos.'
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

            App.ventas.tipoCambio = parseFloat(existeTC.tc);

        }

        const opcionSeleccionada = selectFormaPago.options[selectFormaPago.selectedIndex];

        const requiereCobro = Number(opcionSeleccionada.dataset.requierecobro); 

        return {
            ok: true,
            requiereCobro,
            idtipopago: formaPago
        };    

    }

    // ==============================
    // MODAL
    // ==============================

    function mostrarFormulario(totalVenta,idtipopago){

        const body = `
        <form class="formulario" id="formOV">

        <div class="formulario__campo">
            <label class="formulario__label">Total</label>
            <input type="number" class="formulario__input" id="total" disabled>
        </div>

        <div class="formulario__campo">
            <label class="formulario__label">Importe a Pagar</label>
            <input type="number" class="formulario__input" id="importe" name="importe" />
        </div>
        <div class="formulario__campo">
            <label class="formulario__label">Vuelto</label>
            <input type="number" class="formulario__input" id="vuelto" name="vuelto" disabled />
        </div>

        </form>
        `;

        const footer = `
        <button class="btn btn-primary" id="registrarOV">
            Registrar
        </button>  
        `;

        modal.setContent({
            title:"Registrar Orden de Venta",
            body: body,
            footer: footer
        });

        modal.open();


        // ==============================
        // ELEMENTOS
        // ==============================
 

        const inputTotal = document.querySelector("#total");
        const inputPagar = document.querySelector("#importe");
        const inputVuelto = document.querySelector('#vuelto');   


      
        inputTotal.value = totalVenta.toFixed(2);
        inputPagar.value = totalVenta.toFixed(2);


      if (inputPagar) {
        inputPagar.addEventListener('input', () => {
          const imp = parseFloat(inputPagar.value) || 0;
          const vuelto = imp - (parseFloat(inputTotal.value) || 0);
          inputVuelto.value = vuelto.toFixed(2);
        });
      }

        document.getElementById("registrarOV").addEventListener("click", async function(e){

            e.preventDefault();
            
            RegistraVenta({
                idtipopago,
                total: parseFloat(inputTotal.value),         
                importe: parseFloat(inputPagar.value) || 0,
                vuelto: parseFloat(inputVuelto.value) || 0
            });

        });

    }


    // ==============================
    // CLICK BOTON
    // ==============================

    btnGenerar.addEventListener("click", async function(e){

        e.preventDefault();

        const validacion = await validarVenta();

        if(!validacion) return;

        const totalVenta = App.ventas.totales.total;

        if(validacion.requiereCobro === 1){

            mostrarFormulario(totalVenta, validacion.idtipopago);

        }else{

            RegistraVenta({
                idtipopago: validacion.idtipopago,
                total: totalVenta
            });

        }

    });

    async function RegistraVenta(datos){
    
        const payload = {

            cabecera:{
                idorden: document.getElementById('idorden')?.value || 0,
                fecha: document.getElementById('fecha_venta')?.value,
                idcliente: App.ventas.idcliente,
                idlista: App.ventas.idlista,
                idtipopago: datos.idtipopago,
                observacion: document.getElementById('observacion_venta')?.value || '',
                idmoneda: App.ventas.moneda,
                subtotal: App.ventas.totales.subtotal,
                impuesto: App.ventas.totales.impuesto,
                total: datos.total,
                tipocambio: App.ventas.tipoCambio,                
                tipocambio_oficial: App.ventas.tipoCambio_oficial

            },

            detalle: App.ventas.articulos

        };

        // ======================
        // DEFINIR URL
        // ======================
        const esEdicion = !!App.ventas.idorden;
        
        const url = esEdicion
            ? '/admin/gestion/ventas/orden/editar'
            : '/admin/gestion/ventas/orden/generar';

        try{

            const res = await fetch(url, {
                method:'POST',
                headers:{'Content-Type':'application/json'},
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if(!data.ok){

                Swal.fire("Error",data.mensaje,"error");
                return;

            }

            modal.close();
            Swal.fire({
                icon: 'success',
                title: esEdicion
                    ? `OV ${data.numero} actualizada`
                    : `OV ${data.numero} generada`,
                text: '¿Desea imprimir la orden de venta?',
                showCancelButton: true,
                confirmButtonText: 'Sí, imprimir',
                cancelButtonText: 'No'
            }).then(result => {

                if (result.isConfirmed) {
                    window.open(
                        `/admin/gestion/ventas/orden/imprimir?id=${data.idorden}`,
                        '_blank'
                    );
                }
             
                resetVenta();
            });

        }catch(error){

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