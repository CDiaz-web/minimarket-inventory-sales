import Swal from "sweetalert2";

export function initProductosTienda() {

    document.addEventListener("click", async (e) => {

        const boton = e.target.closest(".btn-editar");
        if (!boton) return;

        e.preventDefault();

        const id = boton.dataset.id;
        const stockMin = boton.dataset.min;
        const stockMax = boton.dataset.max;

        const { value: formValues } = await Swal.fire({
            title: "Editar Stock",
            html: `
                <label style="display:block; text-align:left;">Stock mínimo:</label>
                <input id="swal-min" type="number" step="0.01" class="swal2-input" value="${stockMin}">
                
                <label style="display:block; text-align:left;">Stock máximo:</label>
                <input id="swal-max" type="number" step="0.01" class="swal2-input" value="${stockMax}">
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: "Guardar",
            cancelButtonText: "Cancelar",
            preConfirm: () => {
                return {
                    id: id,
                    stock_min: document.getElementById("swal-min").value,
                    stock_max: document.getElementById("swal-max").value
                };
            }
        });

        if (!formValues) return;

        try {
            const res = await fetch("/admin/mantenimiento/productos/tiendaproductos/editar", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formValues)
            });

            const data = await res.json();

            if (data.success) {
                await Swal.fire("¡Éxito!", "Stock actualizado correctamente", "success");
                location.reload();
            } else {
                Swal.fire("Error", data.mensaje || "No se pudo actualizar", "error");
            }

        } catch (error) {
            console.error(error);
            Swal.fire("Error", "Error de conexión", "error");
        }

    });
}