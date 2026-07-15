export function resetVenta(){

    // limpiar artículos
    App.ventas.articulos = [];

    // limpiar totales
    App.ventas.totales = {
        subtotal:0,
        impuesto:0,
        total:0
    };

    // limpiar tabla
    const tbody = document.querySelector("#tablaArticulosVentas tbody");
    if(tbody) tbody.innerHTML = "";

    // reiniciar total visual
    const totalVenta = document.querySelector("#totalVenta");
    if(totalVenta) totalVenta.textContent = "0.00";

    // limpiar cliente
    App.ventas.idcliente = null;

    const clienteInput = document.querySelector("#buscarCliente");
    if(clienteInput) clienteInput.value = "";

    // limpiar lista de precios
    App.ventas.idlista = null;

    const listaSelect = document.querySelector("#buscarLista");
    if(listaSelect) listaSelect.value = "";

    // enfocar búsqueda de producto
    const buscarProducto = document.querySelector("#buscarProducto");
    if(buscarProducto) buscarProducto.value = "";

}