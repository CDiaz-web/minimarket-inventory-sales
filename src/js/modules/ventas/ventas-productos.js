import { agregarProducto } from './ventas-articulos.js';
import Swal from "sweetalert2";
let productosCache = [];

export function initProductosVenta(){

    $("#buscarProducto").autocomplete({

        minLength:2,

        source:function(request,response){

            if(!App.ventas.idlista){

                mostrarAlerta(
                    "warning",
                    "Seleccione una lista",
                    "Debe seleccionar un cliente o lista de precios antes de buscar productos."
                );

                return false;
            }

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
                    tiene_stock: p.tiene_stock,
                    stock: p.stock_actual,
                    stock_comprometido: p.stock_comprometido,
                    stock_disponible: p.stock_actual - p.stock_comprometido,
                    categoria: p.categoria,
                    precio: p.venta,
                    unidad: p.unidad
                }));

            response(resultados);

        },

        select:function(event,ui){

            event.preventDefault();

            agregarProducto(ui.item);

            $("#buscarProducto").val('').focus();

        }

    });

}

export function cargarProductosPorLista(idLista){

    if(!idLista) return;   

    if(App.ventas.idlista !== idLista){
        productosCache = [];
    }

    $.ajax({

        url:'/api/productosporlista',
        type:'GET',
        data:{ idlista:idLista },
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