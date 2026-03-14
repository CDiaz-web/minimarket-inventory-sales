import Swal from "sweetalert2";

export function initUsuariosTiendas() {

    /* ==========================
       CARGAR TIENDAS ASIGNADAS
    ========================== */
    async function cargarTiendasAsignadas(idUsuario) {

        const tbody = document.querySelector("#tabla-tiendas tbody");
        if (!tbody) return;

        tbody.innerHTML = `
            <tr>
                <td colspan="2" style="text-align:center">Cargando...</td>
            </tr>
        `;

        try {
            const res = await fetch(
                `/admin/seguridad/usuarios/tiendasasignadas?idusuario=${idUsuario}`
            );
            const tiendas = await res.json();

            tbody.innerHTML = "";

            if (!tiendas.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="2" style="text-align:center">
                            No hay tiendas asignadas
                        </td>
                    </tr>
                `;
                return;
            }

            tiendas.forEach(tienda => {
                const tr = document.createElement("tr");

                tr.innerHTML = `
                    <td style="padding:6px; border:1px solid #ddd">
                        ${tienda.tienda}
                    </td>
                    <td style="text-align:center; border:1px solid #ddd">
                        <button 
                            class="btn-eliminar-tienda"
                            data-idusuario="${tienda.idusuario}"
                            data-idtienda="${tienda.idtienda}">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
            });

        } catch (error) {
            console.error(error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="2">Error al cargar</td>
                </tr>
            `;
        }
    }


    /* ==========================
       CARGAR TIENDAS ACTIVAS
    ========================== */
    async function cargarTiendas() {

        const select = document.getElementById("swal-tienda");
        if (!select) return;

        select.innerHTML = '<option value="">Cargando...</option>';

        try {
            const res = await fetch("/admin/configuracion/tiendas/activas");
            const tiendas = await res.json();

            select.innerHTML = '<option value="">-- Seleccione tienda --</option>';

            tiendas.forEach(tienda => {
                const option = document.createElement("option");
                option.value = tienda.id;
                option.textContent = tienda.nombre;
                select.appendChild(option);
            });

        } catch (error) {
            console.error(error);
            select.innerHTML = '<option value="">Error al cargar tiendas</option>';
        }
    }

    /* ==========================
       ELIMINAR TIENDA ASIGNADA
    ========================== */
    document.addEventListener("click", async (e) => {

        const btnEliminar = e.target.closest(".btn-eliminar-tienda");
        if (!btnEliminar) return;

        e.preventDefault();
        e.stopPropagation(); 
        e.stopImmediatePropagation(); 
        const idTienda = btnEliminar.dataset.idtienda;
        const idUsuario = btnEliminar.dataset.idusuario;

        const confirmar = confirm("¿Deseas eliminar esta tienda asignada?");
        if (!confirmar) return;
        
        try {
            const res = await fetch(
                "/admin/seguridad/usuarios/eliminartienda",
                {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        idusuario: idUsuario,
                        idtienda: idTienda
                    })
                }
            );

            const data = await res.json();

            if (!data.success) {
                Swal.fire("Error", data.msg || "No se pudo eliminar", "error");
                return;
            }

            cargarTiendasAsignadas(idUsuario);

        } catch (error) {
            console.error(error);
            Swal.fire("Error", "Error de comunicación", "error");
        }
    });


    /* ==========================
       ABRIR MODAL ASIGNAR TIENDA
    ========================== */
    document.addEventListener("click", async (e) => {

        const btn = e.target.closest(".btn-tienda");
        if (!btn) return;

        e.preventDefault();

        const idUsuario = btn.dataset.id;

        await Swal.fire({
            title: "Asignar Tienda",
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: "Cerrar",
            width: "650px",
            html: `
                <div style="text-align:left">

                    <label style="font-weight:600">Seleccionar tienda</label>

                    <div style="display:flex; gap:10px; margin-top:5px">
                        <select id="swal-tienda" class="swal2-input" style="flex:1"></select>

                        <button type="button" id="btn-agregar-tienda" 
                            class="swal2-confirm swal2-styled">
                            +
                        </button>
                    </div>

                    <hr style="margin:15px 0">

                    <table style="width:100%; border-collapse:collapse" id="tabla-tiendas">
                        <thead>
                            <tr style="background:#f3f3f3">
                                <th style="padding:6px; border:1px solid #ddd">Tienda</th>
                                <th style="padding:6px; border:1px solid #ddd; width:80px"></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            `,
            didOpen: () => {

                cargarTiendas();
                cargarTiendasAsignadas(idUsuario);

                document
                    .getElementById("btn-agregar-tienda")
                    .addEventListener("click", async () => {

                        const select = document.getElementById("swal-tienda");
                        const idTienda = select.value;

                        if (!idTienda) {
                            Swal.showValidationMessage("Seleccione una tienda");
                            return;
                        }

                        try {
                            const res = await fetch(
                                "/admin/seguridad/usuarios/asignartienda",
                                {
                                    method: "POST",
                                    headers: { "Content-Type": "application/json" },
                                    body: JSON.stringify({
                                        idusuario: idUsuario,
                                        idtienda: idTienda
                                    })
                                }
                            );

                            const data = await res.json();

                            if (!data.success) {
                                Swal.showValidationMessage(
                                    data.msg || "Error al asignar"
                                );
                                return;
                            }

                            select.value = "";
                            cargarTiendasAsignadas(idUsuario);

                        } catch (error) {
                            console.error(error);
                            Swal.showValidationMessage(
                                "Error de comunicación"
                            );
                        }
                    });
            }
        });

    });

}