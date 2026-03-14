
export function initInventarios() {

    const tablaDetalle = document.querySelector("#tabla-detalle tbody");
    const btnAgregar = document.querySelector("#btn-agregar-detalle");                                            
    const selectTipo = document.querySelector("#codigotipo");
    const campoTiendaDestino = document.querySelector("#campo-tienda-destino");
    const form = document.querySelector("#form-inventario");

    if (!tablaDetalle || !btnAgregar || !selectTipo || !campoTiendaDestino || !form) return;

    let esCompra = false;

    // ===============================
    // CAMBIO TIPO MOVIMIENTO
    // ===============================
    selectTipo.addEventListener("change", (e) => {

        const codigotipo = e.target.value;

        esCompra = (codigotipo === "COMPRA");

        tablaDetalle.querySelectorAll(".costo-unitario").forEach(input => {
            input.disabled = !esCompra;
        });

        if (codigotipo === "TRANS_SAL") {

            campoTiendaDestino.style.display = "block";

        } else {

            campoTiendaDestino.style.display = "none";
            document.querySelector("#idtienda_relacion").value = "";

        }

    });


    // ===============================
    // AGREGAR PRODUCTO
    // ===============================

        
        btnAgregar.addEventListener("click", () => {

            const index = tablaDetalle.querySelectorAll("tr").length;

            const nuevaFila = document.createElement("tr");

            nuevaFila.innerHTML = `
                <td>
                    <select name="detalle[${index}][idproducto]" class="formulario__input select-producto1" required>
                        <option value="">-- Seleccionar --</option>
                        ${window.productosOptions}
                    </select>
                </td>
                <td>
                    <input type="text" class="formulario__input unidad1" readonly>
                </td>
                <td>
                    <input type="number" class="formulario__input cantidad1" step="0.01" name="detalle[${index}][cantidad]" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="detalle[${index}][costo_unitario]" class="formulario__input costo-unitario" ${esCompra ? "" : "disabled"}>
                </td>
                <td>
                    <div class="table__acciones">
                        <button type="button" class="boton boton--danger"><i class="fa-solid fa-circle-xmark"></i></button>
                    </div>
                </td>
            `;

            tablaDetalle.appendChild(nuevaFila);

        });

    
    // ===============================
    // ELIMINAR FILA
    // ===============================
    tablaDetalle.addEventListener("click", (e) => {

        if (e.target.classList.contains("boton--danger")) {
            e.target.closest("tr").remove();
        }

    });


    // ===============================
    // CAMBIAR UNIDAD
    // ===============================
    tablaDetalle.addEventListener("change", (e) => {

        if (e.target.classList.contains("select-producto1")) {

            const selectedOption = e.target.options[e.target.selectedIndex];
            const unidad1 = selectedOption.dataset.unidad || "";

            e.target.closest("tr").querySelector(".unidad1").value = unidad1;

        }

    });


    // ===============================
    // VALIDAR FORM
    // ===============================
    form.addEventListener("submit", (e) => {

        const filas = tablaDetalle.querySelectorAll("tr");

        if (filas.length === 0) {

            e.preventDefault();
            alert("Debe agregar al menos un producto.");

        }

    });

}