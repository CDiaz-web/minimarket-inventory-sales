import { agregarProducto } from './compras-articulos.js';
import Swal from "sweetalert2";
let productosCache = [];


export function initProductosCompra(){
    
    $("#buscarProductoCompra").autocomplete({
        
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
                    costo:p.costo,              
                    unidad: p.unidad
                }));

            response(resultados);

        },

        select:function(event,ui){

            event.preventDefault();

            agregarProducto(ui.item);

            $("#buscarProductoCompra").val('').focus();

        }

    });

}

export function cargarProductos(){
   
    $.ajax({

        url:'/api/productosporcompra',
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