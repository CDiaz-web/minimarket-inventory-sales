import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-editar").forEach(btn => {
        btn.addEventListener("click", async function (e) {
            e.preventDefault();

            const id = this.dataset.id;
            const stockMin = this.dataset.min;
            const stockMax = this.dataset.max;

            const { value: formValues } = await Swal.fire({
                title: "Editar Stock",
                html: `
                    <label for="swal-min" style="display:block; text-align:left;">Stock mínimo:</label>
                    <input id="swal-min" type="number" step="0.01" class="swal2-input" value="${stockMin}">
                    
                    <label for="swal-max" style="display:block; text-align:left;">Stock máximo:</label>
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
                    }
                }
            });

            if (formValues) {
                // Enviar actualización al backend
                fetch("/admin/mantenimiento/productos/tiendaproductos/editar", {                    
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(formValues)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("¡Éxito!", "Stock actualizado correctamente", "success")
                            .then(() => location.reload());
                    } else {
                        Swal.fire("Error", "No se pudo actualizar el stock", "error");
                    }
                })
                .catch(err => console.error(err));
            }
        });
    });
});
