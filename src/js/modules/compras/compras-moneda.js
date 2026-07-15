import Swal from "sweetalert2";

import { recalcularPreciosCompra } from "./compras-articulos.js";

export function initCompraMoneda(){

    const selectMoneda = document.getElementById("idmoneda");
    const inputFecha = document.getElementById("fecha_compra");

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

            App.compras.moneda = monedaBase;
            simbolo01.textContent = "$";
            simbolo02.textContent = "$";
            simbolo03.textContent = "$";
            App.compras.tipoCambio = 1;

            if(!App.compras.tipoCambio){
                console.warn('No hay tipo de cambio, no se recalcula');
                return;
            }

            recalcularPreciosCompra();

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
                App.compras.tipoCambio = null;

                // mostrar --
                const inputTC = document.getElementById('tipoCambio');
                if(inputTC) inputTC.value = '--';

                return; //  BLOQUEA TODO
            }

            // ejecuta si esta conforme
            App.compras.tipoCambio = parseFloat(existeTC.tc);
        }
        App.compras.moneda = monedaSeleccionada;
        if(monedaSeleccionada == monedaBase){
            simbolo01.textContent = "$";
            simbolo02.textContent = "$";
            simbolo03.textContent = "$";
        }else{
            simbolo01.textContent = "S/";
            simbolo02.textContent = "S/";
            simbolo03.textContent = "S/";
        }

        if(!App.compras.tipoCambio){
            console.warn('No hay tipo de cambio, no se recalcula');
            return;
        }

        recalcularPreciosCompra();
      

    });

}

export function initFechaCompra(){

    const inputFecha = document.getElementById("fecha_compra");
    if(!inputFecha) return;

    actualizarTipoCambio();

    inputFecha.addEventListener("change", async function(){

        const moneda = App.compras.moneda;
        const monedaBase = window.APP.config.moneda_base;
        const validarTC = window.APP.config.validar_tc;        

        if(validarTC == 1){

            const fecha = document.getElementById('fecha_compra')?.value;
            
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

            App.compras.tipoCambio = parseFloat(existeTC.tc);
            
            if(moneda == monedaBase) return;

            if(!App.compras.tipoCambio){
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

        const response = await fetch('/admin/gestion/compras/orden/validarTipoCambio', {
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

    const inputFecha = document.getElementById('fecha_compra');
    const inputTC = document.getElementById('tipoCambio');

    if(!inputFecha || !inputTC) return;

    const fecha = inputFecha.value;

    if(!fecha){
        inputTC.value = '--';
        return;
    }

    const data = await verificarTipoCambio(fecha);

    if(!data.success){
        inputTC.value = '--';
        App.compras.tipoCambio = null; 
        return;
    }

    const tc = parseFloat(data.tc);
    const tc_oficial = parseFloat(data.tc_oficial);

    inputTC.value = tc.toFixed(3);

    App.compras.tipoCambio = tc;
    App.compras.tipoCambio_oficial = tc_oficial;
}
