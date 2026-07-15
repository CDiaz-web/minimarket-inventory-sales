import Swal from "sweetalert2";

import { recalcularPreciosVenta } from "./ventas-articulos.js";

export function initVentaMoneda(){

    const selectMoneda = document.getElementById("idmoneda");
    const inputFecha = document.getElementById("fecha_venta");

    if(!selectMoneda) return;
    if(!inputFecha) return;

    selectMoneda.addEventListener("change", async function(){

        const monedaSeleccionada = this.value;
        const monedaBase = window.APP.config.moneda_base; 
        const validarTC = window.APP.config.validar_tc;

        const simbolo01 = document.getElementById("simboloMoneda01");     
        const simbolo02 = document.getElementById("simboloMoneda02");     
        const simbolo03 = document.getElementById("simboloMoneda03");  

        if(monedaSeleccionada == monedaBase){

            App.ventas.moneda = monedaBase;
            simbolo01.textContent = "$";
            simbolo02.textContent = "$";
            simbolo03.textContent = "$";
            App.ventas.tipoCambio = 1;

            if(!App.ventas.tipoCambio){
                console.warn('No hay tipo de cambio, no se recalcula');
                return;
            }

            recalcularPreciosVenta();

            return;
        }

        if(validarTC == 1){

            const fecha = inputFecha.value;

            if(!fecha){

                Swal.fire({
                    icon:'warning',
                    title:'Fecha requerida',
                    text:'Debe seleccionar la fecha de la compra.'
                });           
                return;
            }

            const existeTC = await verificarTipoCambio(fecha);

            if(!existeTC.success){

                Swal.fire({
                    icon:'warning',
                    title:'Tipo de cambio requerido',
                    text:'No existe tipo de cambio para la fecha seleccionada.'
                });

                // CLAVE: limpiar estado
                App.ventas.tipoCambio = null;

                // mostrar --
                const inputTC = document.getElementById('tipoCambio_venta');
                if(inputTC) inputTC.value = '--';
                
                return;
            }
           
            // ejecuta si esta conforme
            App.ventas.tipoCambio = parseFloat(existeTC.tc);
        }
        App.ventas.moneda = monedaSeleccionada;
        if(monedaSeleccionada == monedaBase){
            simbolo01.textContent = "$";
            simbolo02.textContent = "$";
            simbolo03.textContent = "$";
        }else{
            simbolo01.textContent = "S/";
            simbolo02.textContent = "S/";
            simbolo03.textContent = "S/";
        }

        if(!App.ventas.tipoCambio){
            console.warn('No hay tipo de cambio, no se recalcula');
            return;
        }

        recalcularPreciosVenta();     

    });
}

export function initFechaVenta(){

    const inputFecha = document.getElementById("fecha_venta");
    if(!inputFecha) return;

    actualizarTipoCambio();

    inputFecha.addEventListener("change", async function(){

        const moneda = App.ventas.moneda;
        const monedaBase = window.APP.config.moneda_base;
        const validarTC = window.APP.config.validar_tc;        

        if(validarTC == 1){

            const fecha = document.getElementById('fecha_venta')?.value;
            
            const existeTC = await verificarTipoCambio(fecha);
            actualizarTipoCambio();
            if(!existeTC.success){

                Swal.fire({
                    icon:'warning',
                    title:'Tipo de cambio requerido',
                    text:'No existe tipo de cambio para la fecha seleccionada.'
                });

                return;
            }

            App.ventas.tipoCambio = parseFloat(existeTC.tc);
            
            if(moneda == monedaBase) return;

            if(!App.ventas.tipoCambio){
                console.warn('No hay tipo de cambio, no se recalcula');
                return;
            }

            recalcularPreciosCompra();
        }

    });

}

export async function verificarTipoCambio(fecha){

    try{

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

export async function actualizarTipoCambio(){

    const inputFecha = document.getElementById('fecha_venta');
    const inputTC = document.getElementById('tipoCambio_venta');

    if(!inputFecha || !inputTC) return;

    const fecha = inputFecha.value;

    if(!fecha){
        inputTC.value = '--';
        return;
    }

    const data = await verificarTipoCambio(fecha);

    if(!data.success){
        inputTC.value = '--';
        App.ventas.tipoCambio = null; 
        return;
    }

    const tc = parseFloat(data.tc);
    const tc_oficial = parseFloat(data.tc_oficial);

    inputTC.value = tc.toFixed(3);

    App.ventas.tipoCambio = tc;
    App.ventas.tipoCambio_oficial = tc_oficial;
}

