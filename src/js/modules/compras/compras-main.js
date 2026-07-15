export function resetCompra(){

    // limpiar artículos
    App.compras.articulos = [];

    // limpiar totales
    App.compras.totales = {
        subtotal:0,
        impuesto:0,
        total:0
    };

    // limpiar tabla
    const tbody = document.querySelector("#tablaArticulosCompras tbody");
    if(tbody) tbody.innerHTML = "";

    // reiniciar total visual
    const totalCompra = document.querySelector("#totalCompra");
    if(totalCompra) totalCompra.textContent = "0.00";

    // limpiar proveedor
    App.compras.idproveedor = null;

    const proveedorInput = document.querySelector("#buscarProveedor");
    if(proveedorInput) proveedorInput.value = "";

    // enfocar búsqueda de producto
    const buscarProducto = document.querySelector("#buscarProducto");
    if(buscarProducto) buscarProducto.value = "";

}