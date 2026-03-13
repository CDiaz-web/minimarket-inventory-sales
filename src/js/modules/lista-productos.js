import Swal from "sweetalert2";
import { initTables } from "../ui/table.js";

export function initListaProductos() {

    document.addEventListener("click", async (e) => {

        const btn = e.target.closest('[data-action="abrir-productos"]');
        if (!btn) return;

        e.preventDefault();

        const idLista = btn.dataset.id;

        await Swal.fire({
            title: "Asignar Producto",
            width: "900px",
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: "Cerrar",

            html: `
                <div class="modal-productos">

                    <!-- BUSCADOR -->
                    <input 
                        type="text"
                        id="buscar-producto"
                        placeholder="Buscar producto..."
                        class="swal2-input"
                    >

                    <!-- RESULTADOS AJAX -->
                    <div id="resultados-productos"></div>

                    <div id="paginacion-productos" 
                         style="margin:10px 0; text-align:center"></div>

                    <!-- PRECIO + AGREGAR -->
                    <div style="display:flex; gap:10px; margin:15px 0">
                        <input 
                            type="number"
                            id="precio-producto"
                            placeholder="Precio"
                            step="0.01"
                            min="0"
                            class="swal2-input"
                            style="flex:1"
                        >
                        <button id="btn-agregar-producto" 
                                class="swal2-confirm swal2-styled">
                            +
                        </button>
                    </div>

                    <hr>

                    <!-- TABLA ASIGNADOS -->
                    <input 
                        type="text"
                        placeholder="Buscar en asignados..."
                        data-table-search="tabla-asignados"
                        class="swal2-input"
                    >

                    <table 
                        id="tabla-asignados"
                        data-table
                        data-page-size="5"
                        style="width:100%; border-collapse:collapse">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div data-table-pagination="tabla-asignados"></div>

                </div>
            `,

            didOpen: () => {

                let productoSeleccionado = null;
                let paginaActual = 1;
                let terminoActual = "";

                let productosAsignados = [];

                const resultadosDiv = document.getElementById("resultados-productos");


                /* ===========================
                   BUSCAR PRODUCTOS (SERVER)
                =========================== */

                async function buscarProductos(q = "", page = 1) {

                    resultadosDiv.innerHTML = "Buscando...";

                    const res = await fetch(
                        `/admin/mantenimiento/productos/productos/buscar?q=${q}&page=${page}`
                    );

                    const data = await res.json();

                    renderResultados(data.data);
                    renderPaginacion(data.page, data.last_page);
                }

                // function renderResultados(productos) {

                //     if (!productos.length) {
                //         resultadosDiv.innerHTML = "No hay resultados";
                //         return;
                //     }

                //     resultadosDiv.innerHTML = productos.map(p => `
                //         <div class="item-producto"
                //              data-id="${p.id}"
                //              style="padding:6px; cursor:pointer; border-bottom:1px solid #eee">
                //             ${p.nombre}
                //         </div>
                //     `).join("");          
                // }
                    function renderResultados(productos) {

                        if (!productos.length) {
                            resultadosDiv.innerHTML = "No hay resultados";
                            return;
                        }

                        resultadosDiv.innerHTML = productos.map(p => {

                            const asignado = productosAsignados.includes(String(p.id));

                            return `
                                <div class="item-producto"
                                    data-id="${p.id}"
                                    style="
                                        padding:6px;
                                        border-bottom:1px solid #eee;
                                        cursor:${asignado ? 'not-allowed' : 'pointer'};
                                        color:${asignado ? '#999' : '#000'};
                                        background:${asignado ? '#f5f5f5' : ''};
                                    "
                                    ${asignado ? 'data-asignado="1"' : ''}
                                >
                                    ${p.nombre} ${asignado ? '(ya asignado)' : ''}
                                </div>
                            `;
                        }).join("");
                    }
                function renderPaginacion(page, lastPage) {

                    const cont = document.getElementById("paginacion-productos");
                    cont.innerHTML = "";

                    for (let i = 1; i <= lastPage; i++) {

                        const btn = document.createElement("button");
                        btn.textContent = i;

                        if (i === page) btn.classList.add("active");

                        btn.addEventListener("click", () => {
                            paginaActual = i;
                            buscarProductos(terminoActual, i);
                        });

                        cont.appendChild(btn);
                    }
                }

                /* ===========================
                   EVENTOS
                =========================== */

                document.getElementById("buscar-producto")
                    .addEventListener("input", (e) => {

                        terminoActual = e.target.value;
                        paginaActual = 1;

                        buscarProductos(terminoActual, 1);
                    });

                resultadosDiv.addEventListener("click", (e) => {

                    const item = e.target.closest(".item-producto");
                    if (!item) return;


                    if (item.dataset.asignado) {
                        Swal.showValidationMessage("El producto ya está asignado");
                        return;
                    }

                    productoSeleccionado = item.dataset.id;

                    document.querySelectorAll(".item-producto")
                        .forEach(i => i.style.background = "");

                    item.style.background = "#e6f0ff";
                });

                document.getElementById("btn-agregar-producto")
                    .addEventListener("click", async () => {

                        const precio = parseFloat(
                            document.getElementById("precio-producto").value
                        );

                        if (!productoSeleccionado) {
                            Swal.showValidationMessage("Seleccione producto");
                                setTimeout(() => {
                                    const msg = document.querySelector(".swal2-validation-message");
                                    if (msg) msg.remove();
                                }, 1500);
                            return;
                        }

                        if (!precio || precio <= 0) {
                            Swal.showValidationMessage("Precio inválido");
                                setTimeout(() => {
                                    const msg = document.querySelector(".swal2-validation-message");
                                    if (msg) msg.remove();
                                }, 1500);
                            return;
                        }
                        // VALIDAR DUPLICADO
                            const existe = document.querySelector(
                                `.btn-eliminar-producto[data-idproducto="${productoSeleccionado}"]`
                            );

                            if (existe) {
                                Swal.showValidationMessage("El producto ya está asignado");

                                setTimeout(() => {
                                    const msg = document.querySelector(".swal2-validation-message");
                                    if (msg) msg.remove();
                                }, 1500);

                                return;
                            }

                        const res = await fetch(
                            "/admin/mantenimiento/listas/asignarproducto",
                            {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    idlista: idLista,
                                    idproducto: productoSeleccionado,
                                    precio: precio
                                })
                            }
                        );

                        const data = await res.json();

                        if (!data.success) {
                            Swal.showValidationMessage(data.msg || "No se pudo agregar");
                            return;
                        }

                        // mensaje corto
                        Swal.showValidationMessage("Producto agregado");

                        setTimeout(() => {
                            const msg = document.querySelector(".swal2-validation-message");
                            if (msg) msg.remove();
                        }, 1500);

                        // limpiar selección
                        document.getElementById("buscar-producto").value = "";
                        productoSeleccionado = null;

                        document.getElementById("precio-producto").value = "";
                        document.getElementById("buscar-producto").focus();

                        document.querySelectorAll(".item-producto")
                            .forEach(i => i.style.background = "");

                        cargarAsignados();
                    });

                /* ===========================
                   CARGAR ASIGNADOS
                =========================== */

                async function cargarAsignados() {

                    const tbody = document.querySelector("#tabla-asignados tbody");

                    const res = await fetch(
                        `/admin/mantenimiento/listas/productosasignados?idlista=${idLista}`
                    );

                    const productos = await res.json();
                    //linea para bloquear productos asignados
                    productosAsignados = productos.map(p => String(p.idproducto));

                    tbody.innerHTML = "";

                    productos.forEach(p => {

                        const tr = document.createElement("tr");

                        tr.innerHTML = `
                            <td>${p.producto}</td>
                            <td>
                                <input 
                                    type="number"
                                    class="precio"
                                    value="${parseFloat(p.precio).toFixed(2)}"
                                    step="0.01"
                                    data-idlista="${p.idlista}"
                                    data-idproducto="${p.idproducto}"   
                                    style="width:90px; text-align:right;"
                                >
                            </td>
                            <td>
                                <button 
                                    class="btn-eliminar-producto"
                                    data-idlista="${p.idlista}"
                                    data-idproducto="${p.idproducto}">
                                    ✕
                                </button>
                            </td>
                        `;

                        tbody.appendChild(tr);
                    });

                    initTables(); // solo para asignados
                }



                document.querySelector("#tabla-asignados tbody")
                    .addEventListener("click", async (e) => {

                        const btn = e.target.closest(".btn-eliminar-producto");
                        if (!btn) return;

                        const idProducto = btn.dataset.idproducto;
                        const idLista = btn.dataset.idlista;

                        const confirmar = confirm("¿Deseas eliminar el Producto?");
                        if (!confirmar) return;

                        try {

                            const res = await fetch(
                                "/admin/mantenimiento/listas/eliminarproducto",
                                {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        idlista: idLista,
                                        idproducto: idProducto
                                    })
                                }
                            );

                            const data = await res.json();

                            if (!data.success) {
                                Swal.fire("Error", data.msg || "No se pudo eliminar", "error");
                                return;
                            }

                            // refrescar tabla
                            cargarAsignados();

                        } catch (error) {
                            console.error(error);
                            Swal.fire("Error", "Error de comunicación", "error");
                        }

                });


            document.querySelector("#tabla-asignados tbody")
            .addEventListener("blur", async (e) => {

                const input = e.target.closest(".precio");
                if (!input) return;

                const idLista = input.dataset.idlista;
                const idProducto = input.dataset.idproducto;
                const precio = parseFloat(input.value);

                if (!precio || precio <= 0) {
                    Swal.fire("Error", "Precio inválido", "error");
                    return;
                }

                try {

                    const res = await fetch(
                        "/admin/mantenimiento/listas/actualizaproducto",
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                idlista: idLista,
                                idproducto: idProducto,
                                precio: precio
                            })
                        }
                    );

                    const data = await res.json();

                    if (!data.success) {
                        Swal.fire("Error", data.msg || "No se pudo actualizar", "error");
                        return;
                    }

                    // feedback visual
                    input.style.backgroundColor = "#e6ffe6";

                    setTimeout(() => {
                        input.style.backgroundColor = "";
                    }, 500);

                } catch (error) {
                    console.error(error);
                    Swal.fire("Error", "Error de comunicación", "error");
                }

            }, true); // 👈 importante usar true para blur



                buscarProductos();
                
                cargarAsignados();
            }
        });
    });
}