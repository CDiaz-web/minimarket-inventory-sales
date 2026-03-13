import Swal from "sweetalert2";
import { verificarTipoCambioHoy } from "./ventas-moneda.js";
import { modal } from "../../core/modal-manager.js";
import { resetVenta } from "./ventas-main.js";
export function initOrdenVenta(){

    let tipospago = [];


    const btnGenerar = document.querySelector('#btngenerar');
    if (!btnGenerar) return;


    // ==============================
    // FECHA ACTUAL
    // ==============================

    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${yyyy}-${mm}-${dd}`;



    // ==============================
    // OBTENER DATOS
    // ==============================

    async function obtenerTiposPago(){

        try{
            const res = await fetch('/api/tipopago');
            tipospago = await res.json();
        }catch(error){

            console.error("Error obteniendo tipos de pago", error);
            tipospago = [];
        }

    }



    // ==============================
    // VALIDACIONES
    // ==============================

    async function validarVenta(){

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

            const existeTC = await verificarTipoCambioHoy();

            if(!existeTC.success){

                Swal.fire({
                    icon:'warning',
                    title:'Tipo de cambio requerido',
                    text:'Debe registrar el tipo de cambio del día.'
                });

                return false;
            }

            App.ventas.tipoCambio = parseFloat(existeTC.tc);

        }

        return true;

        

    }



    // ==============================
    // MODAL
    // ==============================

    function mostrarFormulario(totalVenta){

        const body = `
        <form class="formulario" id="formOV">

        <div class="formulario__campo">
            <label class="formulario__label">Forma de Pago</label>
            <select class="formulario__select" id="idtipopago">
            <option value="">-Seleccionar-</option>
            </select>
        </div>

        <div class="formulario__campo">
            <label class="formulario__label">Fecha</label>
            <input type="date" class="formulario__input" id="fecha">
        </div>

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


        <div class="formulario__campo">
            <label class="formulario__label">Observación</label>
            <textarea id="observacion"></textarea>
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

        const selectPago = document.querySelector("#idtipopago");
        const inputFecha = document.querySelector("#fecha");
        const inputTotal = document.querySelector("#total");
        const inputPagar = document.querySelector("#importe");
        const inputVuelto = document.querySelector('#vuelto');
        const inputObservacion = document.querySelector("#observacion");


        // ==============================
        // CARGAR DATOS
        // ==============================

        tipospago.forEach(tp => {

            const opt = document.createElement("option");
            opt.value = tp.id;
            opt.textContent = tp.nombre;

            selectPago.appendChild(opt);

        });


        inputFecha.value = fechaActual;
        inputTotal.value = totalVenta.toFixed(2);
        inputPagar.value = totalVenta.toFixed(2);


      if (inputPagar) {
        inputPagar.addEventListener('input', () => {
          const imp = parseFloat(inputPagar.value) || 0;
          const vuelto = imp - (parseFloat(inputTotal.value) || 0);
          inputVuelto.value = vuelto.toFixed(2);
        });
      }


        // ==============================
        // EVENTOS MODAL
        // ==============================



        // ==============================
        // REGISTRAR
        // ==============================

        document.getElementById("registrarOV").addEventListener("click", async function(e){

            e.preventDefault();

            if(selectPago.value === ""){

                Swal.fire("Forma de pago requerida","","warning");
                return;

            }

            const payload = {

                cabecera:{

                    fecha: inputFecha.value,
                    idcliente: App.ventas.idcliente,
                    idlista: App.ventas.idlista,
                    idtipopago: selectPago.value,
                    observacion: inputObservacion.value,
                    idmoneda: App.ventas.moneda,
                    total: parseFloat(inputTotal.value),
                    tipocambio: App.ventas.tipoCambio

                },

                detalle: App.ventas.articulos

            };


            try{

                const res = await fetch('/admin/gestion/ventas/orden/generar',{

                    method:'POST',
                    headers:{'Content-Type':'application/json'},
                    body: JSON.stringify(payload)

                });

                const data = await res.json();

                if(!data.ok){

                    Swal.fire("Error",data.mensaje,"error");
                    return;

                }

                // Swal.fire({

                //     icon:'success',
                //     title:`OV ${data.numero} generada`

                //     }).then(()=>{

                //     location.reload();

                // });
                modal.close();
                Swal.fire({
                    icon: 'success',
                    title: `OV ${data.numero} generada`,
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

                    // Limpieza siempre                 
                    resetVenta();
                });

            }catch(error){

                console.error(error);

                Swal.fire("Error","No se pudo registrar la orden","error");

            }

        });

    }



    // ==============================
    // CLICK BOTON
    // ==============================

    btnGenerar.addEventListener("click", async function(e){

        e.preventDefault();

        if(!(await validarVenta())) return;

        await Promise.all([
        obtenerTiposPago(),
        ]);

        const totalVenta = App.ventas.totales.total;

        mostrarFormulario(totalVenta);

    });

}