let listasCache = [];
import { cargarProductosPorLista } from './ventas-productos.js';
import { resetVenta } from './ventas-articulos.js';

export function initListasVenta(){

    // Cargar listas una vez
    $.ajax({
        url:"/api/listas",
        type:"GET",
        dataType:"json",
        success:function(data){

            listasCache = data;

            activarAutocomplete();

        }
    });

}

function activarAutocomplete(){

    $("#buscarLista").autocomplete({

        minLength:0,

        source: function(request,response){

            const resultados = listasCache
                .filter(item =>
                    item.descripcion
                        .toLowerCase()
                        .includes(request.term.toLowerCase())
                )
                .map(item => ({
                    label: item.descripcion,
                    value: item.descripcion,
                    id: item.id
                }));

            response(resultados);

        },

        select: function (event, ui) {

            event.preventDefault();
            resetVenta();
            $("#buscarLista").val(ui.item.label);

            App.ventas.idlista = ui.item.id;
            
            cargarProductosPorLista(ui.item.id);

        }

    });

}

export function seleccionarListaPorId(idLista){

    const lista = listasCache.find(l => l.id == idLista);

    if(!lista) return;
    

    $("#buscarLista").val(lista.descripcion);

    App.ventas.idlista = lista.id;    

}

