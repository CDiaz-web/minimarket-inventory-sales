

import { resetCompras } from './compras-articulos.js';
import { cargarProductos } from './compras-productos.js';
export function initProveedoresCompra(){

    $("#buscarProveedor").autocomplete({
        minLength:2,
        source: function(request,response){

            $.ajax({
                url:"/api/proveedores",
                type: "GET",
                data:{term:request.term},
                dataType: "json",
                success:function(data){

                    const resultados = data
                        .filter(item =>
                            item.nombre_proveedor
                                .toLowerCase()
                                .includes(request.term.toLowerCase())
                        )
                        .map(item => ({
                            label: item.nombre_proveedor,
                            value: item.nombre_proveedor,
                            id: item.id
                        }));

                    response(resultados);

                }
            });

        },


            select: async function (event, ui) {
                event.preventDefault();

                const cambiarProveedor = async () => {
                    $("#buscarProveedor").val(ui.item.label);
                    App.compras.idproveedor = ui.item.id;
                    cargarProductos();
                };

                // Si no hay artículos, simplemente cambiar
                if (App.compras.articulos.length === 0) {
                    cambiarProveedor();
                    return;
                }

                // Si el proveedor es el mismo, no hacer nada especial
                if (App.compras.idproveedor == ui.item.id) {
                    cambiarProveedor();
                    return;
                }

                // Confirmar si ya existen artículos
                const result = await Swal.fire({
                    icon: 'warning',
                    title: 'Cambiar proveedor',
                    text: 'Se eliminarán todos los artículos de la orden. ¿Desea continuar?',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar'
                });

                if (!result.isConfirmed) {
                    return;
                }

                resetCompras();
                cambiarProveedor();
            }


    });

}
