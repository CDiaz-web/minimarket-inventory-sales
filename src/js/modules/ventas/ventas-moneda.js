import Swal from "sweetalert2";

import { recalcularPreciosVenta } from "./ventas-articulos.js";

export function initVentaMoneda(){

    const selectMoneda = document.getElementById("idmoneda");

    if(!selectMoneda) return;

    selectMoneda.addEventListener("change", async function(){

        const monedaSeleccionada = this.value;
        const monedaBase = window.APP.config.moneda_base; 
        const validarTC = window.APP.config.validar_tc;

        const simbolo = document.getElementById("simboloMoneda");

        if(monedaSeleccionada == monedaBase){

            App.ventas.moneda = monedaBase;
            simbolo.textContent = "$";
            App.ventas.tipoCambio = 1;
            recalcularPreciosVenta();

            return;
        }

        if(validarTC == 1){

            const existeTC = await verificarTipoCambioHoy();

            if(!existeTC.success){

                Swal.fire({
                    icon:'warning',
                    title:'Tipo de cambio requerido',
                    text:'Debe registrar el tipo de cambio del día.'
                });

                this.value = monedaBase;
                return;
            }
           
            App.ventas.tipoCambio = parseFloat(existeTC.tc);
        }
        App.ventas.moneda = monedaSeleccionada;
        if(monedaSeleccionada == monedaBase){
            simbolo.textContent = "$";
        }else{
            simbolo.textContent = "S/";
        }
        recalcularPreciosVenta();
      

    });

}


export async function verificarTipoCambioHoy(){

    try{

        const fecha = new Date().toISOString().slice(0,10);

        const formData = new FormData();
        formData.append('fecha', fecha);

        const response = await fetch('/admin/gestion/ventas/orden/validarTipoCambio', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        return data;
        
    }catch(error){

        console.error(error);
        return { success:false };

    }

}

