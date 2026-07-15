import { initProductosMovimientos, cargarProductos } from './movimientos-productos.js';
import { initTablaMovimientos, resetMovimientos } from './movimientos-articulos.js';
import {initMovimientoInventario} from './movimientos-inventario.js';
import { initInventariosMov } from "./movimientos-gestion.js";
import Swal from "sweetalert2";

// ======================
// ESTADO DEL MODULO
// ======================

window.App = window.App || {};

App.movimientos = {
    idmovimiento:null,
    idtipo:null,
    idtienda_relacion:0,
    fecha:null,
    observacion:'',
    accion:null,
    esTransferencia:0,
    articulos:[]
};

export function initMovimientos(){

    const inputProductos = document.getElementById('buscarProductoMov');

    if(inputProductos){

        const inputIdMov = document.getElementById('idmovimiento');

        initProductosMovimientos();
        cargarProductos();
        initTablaMovimientos();
        initMovimientoInventario();
        // ======================
        // TIPO MOVIMIENTO
        // ======================

        const selectTipo = document.getElementById('idtipo');
        const selectTienda = document.getElementById('idtienda');
        const inputObservacion = document.getElementById('observacion_movimiento');

        // Tipo movimiento
        selectTipo?.addEventListener('change', () => {

            const opcion =
                selectTipo.options[selectTipo.selectedIndex];

            App.movimientos.idtipo = selectTipo.value || null;
            App.movimientos.accion = opcion.dataset.accion || null;
            App.movimientos.esTransferencia =
                parseInt(opcion.dataset.transferencia || 0);

        });

        // Tienda destino
        selectTienda?.addEventListener('change', () => {

            App.movimientos.idtienda_relacion =
                selectTienda.value || null;

        });
 
        // Observación
        inputObservacion?.addEventListener('input', () => {

            App.movimientos.observacion =
                inputObservacion.value;

        });


        if(selectTipo && selectTienda){
            // al cambiar
            selectTipo.addEventListener(
                'change',
                () => actualizarTransferencia(true)
            );

            // al cargar pantalla
            actualizarTransferencia(false);            
        }

        if(inputIdMov?.value){
            App.movimientos.idmovimiento =
                parseInt(inputIdMov.value);
        }

    }

}

function actualizarTransferencia(mostrarMensaje = true){

    const selectTipo = document.getElementById('idtipo');
    const selectTienda = document.getElementById('idtienda');

    if(!selectTipo || !selectTienda) return;

    if(
        mostrarMensaje &&
        App.movimientos.articulos.length > 0
    ){

        resetMovimientos();

        Swal.fire({
            icon:'info',
            title:'Detalle reiniciado',
            text:'El cambio de tipo de movimiento eliminó los artículos registrados.',
            timer:1500,
            showConfirmButton:false
        });
    }

    const opcion =
        selectTipo.options[selectTipo.selectedIndex];

    const esTransferencia =
        parseInt(opcion.dataset.transferencia);

    selectTienda.disabled = !esTransferencia;

    if(!esTransferencia){
        selectTienda.value = '';
    }
}

    // ======================
    // MODULO GESTION OV
    // ======================

    if(document.querySelector('.btn-anular-mov')){
        initInventariosMov();
    }