import { agregarProducto } from './movimientos-articulos.js';
import Swal from "sweetalert2";
let productosCache = [];


export function initProductosMovimientos(){
    
    $("#buscarProductoMov").autocomplete({
        
        minLength:2,

        source:function(request,response){

            const term = request.term.toLowerCase();

            const resultados = productosCache
                .filter(p =>
                    p.nombre.toLowerCase().includes(term) ||
                    p.codigo.toLowerCase().includes(term)
                )
                .map(p => ({
                    label: `${p.codigo} - ${p.nombre}`,
                    value: p.nombre,
                    id: p.id,
                    codigo: p.codigo,
                    categoria: p.categoria,
                    unidad: p.unidad,
                    tiene_stock: p.tiene_stock,
                    stock_disponible: p.stock_actual - p.stock_comprometido
                     
                }));

            response(resultados);

        },


        select:function(event,ui){

            event.preventDefault();

            const idTipo =
                document.getElementById('idtipo')?.value;

            if(!idTipo){

                mostrarAlerta(
                    "warning",
                    "Tipo de movimiento requerido",
                    "Seleccione primero un tipo de movimiento."
                );

                $("#buscarProductoMov").val('');
                return;
            }

            agregarProducto(ui.item);

            $("#buscarProductoMov").val('').focus();

        }


    });

}

export function cargarProductos(){
   
    $.ajax({

        url:'/api/productosportienda',
        type:'GET', 
        dataType:'json',
        
        success:function(data){

            productosCache = data;

        },

        error:function(){

            console.error('Error al cargar productos');

        }

    });

}

function mostrarAlerta(icono, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        title: titulo,
        text: mensaje,
        confirmButtonText: "Entendido",
        timer: 2000
    });
}