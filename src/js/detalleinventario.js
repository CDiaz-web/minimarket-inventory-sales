const e = document.getElementById("btn-agregar-detalle");
if (e) {

    document.addEventListener("DOMContentLoaded", () => {
        const tablaDetalle = document.querySelector("#tabla-detalle tbody");
        const btnAgregar = document.querySelector("#btn-agregar-detalle");
        const selectTipo = document.querySelector("#codigotipo"); // aquí debe estar tu select de tipo de movimiento
        const campoTiendaDestino = document.querySelector("#campo-tienda-destino");
        const form = document.querySelector("#formMovimiento"); 
        // Estado global: si es compra, habilita costo
        let esCompra = false;
            
            selectTipo.addEventListener("change", (e) => {
            const codigotipo = e.target.value;
            // Ajusta según tu catálogo (ej: id=1 es Compra)     
            esCompra = (codigotipo === "COMPRA");

            // Habilitar o deshabilitar campo costo en filas existentes
            tablaDetalle.querySelectorAll(".costo-unitario").forEach(input => {
                input.disabled = !esCompra;
            });

            // Detectar TRANSFERENCIA
            if (codigotipo === "TRANS_SAL") {
                campoTiendaDestino.style.display = "block";
            } else {
                campoTiendaDestino.style.display = "none";
                document.querySelector("#idtienda_relacion").value = ""; // limpiar selección
            }

        });

        // Agregar fila
        btnAgregar.addEventListener("click", () => {
            const index = tablaDetalle.querySelectorAll("tr").length;
            const nuevaFila = document.createElement("tr");

            nuevaFila.innerHTML = `
                <td>
                    <select name="detalle[${index}][idproducto]" class="select-producto" required>
                        <option value="">-- Seleccionar --</option>
                        ${window.productosOptions}
                    </select>
                </td>
                <td>
                    <input type="text" class="unidad" readonly>
                </td>
                <td>
                    <input type="number" step="0.01" name="detalle[${index}][cantidad]" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="detalle[${index}][costo_unitario]" class="costo-unitario" ${esCompra ? "" : "disabled"}>
                </td>
                <td>
                    <button type="button" class="btn btn-eliminar-detalle">❌</button>
                </td>
            `;
            tablaDetalle.appendChild(nuevaFila);
        });

        // Eliminar fila
        tablaDetalle.addEventListener("click", (e) => {
            if (e.target.classList.contains("btn-eliminar-detalle")) {
                e.target.closest("tr").remove();
            }
        });

        // Cambiar unidad al seleccionar producto
        tablaDetalle.addEventListener("change", (e) => {
            if (e.target.classList.contains("select-producto")) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const unidad = selectedOption.dataset.unidad || "";
                e.target.closest("tr").querySelector(".unidad").value = unidad;
            }
        });

        // ✅ Validación antes de enviar
        form.addEventListener("submit", (e) => {
            const filas = tablaDetalle.querySelectorAll("tr");
            if (filas.length === 0) {
                e.preventDefault();
                alert("Debe agregar al menos un producto en el detalle.");
                return false;
            }
        });

    });
    

}




