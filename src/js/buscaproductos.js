window.articulosSeleccionados = [];// Variable global para almacenar los artículos

var buscaArticulo = document.getElementById('buscarArticulo');
if (buscaArticulo) {
    $(document).ready(function () {

        $("#buscarArticulo").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "/api/productos",
                    type: "GET",
                    data: { term: request.term },
                    dataType: "json",
                    success: function (data) {
                        const resultadosFiltrados = data.filter(item =>
                            item.buscar.toLowerCase().includes(request.term.toLowerCase())
                        );
                        response(resultadosFiltrados);
                    }
                });
            },
            select: function (event, ui) {                
                agregarArticuloATabla(ui.item);
                $("#buscarArticulo").val('').focus();
                return false;
            }
        });

        function agregarArticuloATabla(articulo) {

            var filaExistente = $("#tablaArticulos tbody tr").filter(function () {
                return $(this).data('idproducto') == articulo.id;
            });


            if (filaExistente.length > 0) {
                var cantidadInput = filaExistente.find('.cantidad');
                var nuevaCantidad = parseInt(cantidadInput.val()) + 1;
       
                if (nuevaCantidad <= articulo.stock) {
                    cantidadInput.val(nuevaCantidad);
                    actualizarTotalFila(filaExistente);
                    actualizarVariableGlobal();
                } else {
                    mostrarAlerta("warning", "Stock máximo alcanzado", "No puedes agregar más de la cantidad en stock.");
                }
            } else {

                var fila = `
                <tr class="table__tr"
                    data-idproducto="${articulo.id}"
                    data-precio="${articulo.venta}">
                    
                    <td class="descripcion">${articulo.label}</td>
                    <td>
                        <input type="number" value="1" min="1" max="${articulo.stock}" class="cantidad">
                    </td>
                    <td class="unidad">${articulo.unidad}</td>
                    <td class="precio">${parseFloat(articulo.venta).toFixed(2)}</td>
                    <td class="total">${parseFloat(articulo.venta).toFixed(2)}</td>
                    <td>
                        <button class="aumentar">+</button>
                        <button class="disminuir">-</button>
                        <button class="eliminar">x</button>
                    </td>
                </tr>`;

                $("#tablaArticulos tbody").append(fila);
                
                actualizarVariableGlobal();
            }
            actualizarTotales();
            $("#buscarArticulo").focus();
        }

        $(document).on('input', '.cantidad', function () {
            var cantidadInput = $(this);
            var cantidad = parseInt(cantidadInput.val());
            var maxStock = parseInt(cantidadInput.attr('max'));

            if (cantidad > maxStock) {
                cantidadInput.val(maxStock);
                mostrarAlerta("warning", "Stock máximo alcanzado", "No puedes agregar más de la cantidad en stock.");
            }

            actualizarTotalFila(cantidadInput.closest('tr'));
            actualizarVariableGlobal();
        });

        $(document).on('click', '.aumentar', function () {
            var fila = $(this).closest('tr');
            var cantidadInput = fila.find('.cantidad');
            var maxStock = parseInt(cantidadInput.attr('max'));
            var nuevaCantidad = parseInt(cantidadInput.val()) + 1;
  
            if (nuevaCantidad <= maxStock) {
                cantidadInput.val(nuevaCantidad);
                actualizarTotalFila(fila);
                actualizarVariableGlobal();
            } else {
                mostrarAlerta("warning", "Stock máximo alcanzado", "No puedes agregar más de la cantidad en stock.");
            }
        });

        $(document).on('click', '.disminuir', function () {
            var fila = $(this).closest('tr');
            var cantidadInput = fila.find('.cantidad');
            var nuevaCantidad = Math.max(1, parseInt(cantidadInput.val()) - 1);
            cantidadInput.val(nuevaCantidad);
            actualizarTotalFila(fila);
            actualizarVariableGlobal();
        });

        $(document).on('click', '.eliminar', function () {
            $(this).closest('tr').remove();
            actualizarTotales();
            actualizarVariableGlobal();
        });

        function actualizarTotalFila(fila) {
            const precio   = parseFloat(fila.data('precio'));
            const cantidad = parseInt(fila.find(".cantidad").val());

            if (isNaN(precio) || isNaN(cantidad)) return;

            fila.find(".total").text((cantidad * precio).toFixed(2));
            actualizarTotales();
        }

        function actualizarTotales() {
            var totalGeneral = 0;
            $("#tablaArticulos tbody tr").each(function () {
                var total = parseFloat($(this).find(".total").text());
                totalGeneral += total;
            });
            $("#totalVenta").text(totalGeneral.toFixed(2));
        }

     

        function actualizarVariableGlobal() {
            window.articulosSeleccionados = [];

            $("#tablaArticulos tbody tr").each(function () {
                const fila = $(this);

                window.articulosSeleccionados.push({
                    idproducto: fila.data("idproducto"),
                    nombre: fila.find("td:nth-child(2)").text().trim(),
                    cantidad: parseFloat(fila.find(".cantidad").val()),
                    unidad: fila.find("td:nth-child(4)").text().trim(),
                    precio: parseFloat(fila.find("td:nth-child(5)").text()),
                    total: parseFloat(fila.find(".total").text())
                });
            });
        }


        $("#eliminarTodo").click(function () {
            $("#tablaArticulos tbody").empty();
            actualizarTotales();
            actualizarVariableGlobal();
        });

        function mostrarAlerta(icono, titulo, mensaje) {
            Swal.fire({
                icon: icono,
                title: titulo,
                text: mensaje,
                confirmButtonText: "Entendido",
                timer: 2000
            });
        }

        // Evento para enviar los artículos al modal
        $("#abrirModalVenta").click(function () {
            if (articulosSeleccionados.length === 0) {
                mostrarAlerta("info", "Sin artículos", "Debe seleccionar al menos un artículo para continuar.");
                return;
            }
            $("#modalVenta").modal("show");
            $("#listaArticulosVenta").html(""); // Limpiar lista

            articulosSeleccionados.forEach(articulo => {
                $("#listaArticulosVenta").append(`
                    <tr>
                        <td>${articulo.nombre}</td>
                        <td>${articulo.cantidad}</td>
                        <td>${articulo.unidad}</td>
                        <td>${articulo.precio.toFixed(2)}</td>
                        <td>${articulo.total.toFixed(2)}</td>
                    </tr>
                `);
            });

            $("#totalVentaModal").text($("#totalVenta").text()); // Mostrar el total en el modal
        });

    });
}
