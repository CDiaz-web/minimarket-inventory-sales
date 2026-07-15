
import { seleccionarListaPorId } from './ventas-listas.js';
import { cargarProductosPorLista } from './ventas-productos.js';
import { resetVenta } from './ventas-articulos.js';
export function initClientesVenta(){

    $("#buscarCliente").autocomplete({
        minLength:2,
        source: function(request,response){

            $.ajax({
                url:"/api/clientes",
                type: "GET",
                data:{term:request.term},
                dataType: "json",
                success:function(data){

                    const resultados = data
                        .filter(item =>
                            item.nombre_cliente
                                .toLowerCase()
                                .includes(request.term.toLowerCase())
                        )
                        .map(item => ({
                            label: item.nombre_cliente,
                            value: item.nombre_cliente,
                            id: item.id,
                            idlista: item.idlista
                        }));

                    response(resultados);

                }
            });

        },

        select: function (event, ui) {

            event.preventDefault();
            resetVenta();
            $("#buscarCliente").val(ui.item.label);

            App.ventas.idcliente = ui.item.id;            
            seleccionarListaPorId(ui.item.idlista);
            cargarProductosPorLista(ui.item.idlista);
        }

    });

}

