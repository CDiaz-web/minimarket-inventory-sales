import Swal from "sweetalert2";


document.addEventListener("DOMContentLoaded", () => {
    const fechaInput = document.getElementById("fecha");
    const btnSunat = document.getElementById("btnTraerSunat");
    const compraInput = document.getElementById("compra_oficial");
    const ventaInput = document.getElementById("venta_oficial");

    const compraMercado = document.getElementById("compra_mercado");
    const ventaMercado = document.getElementById("venta_mercado");

    if (btnSunat) {
        btnSunat.addEventListener("click", async () => {
            if (!fechaInput.value) {
                Swal.fire("Atención", "Primero selecciona una fecha.", "warning");
                return;
            }
            console.log("Consultando tipo de cambio para:", fechaInput.value);
            // Validación si la fecha es domingo (SUNAT no publica ese día)
            const fechaSeleccionada = new Date(fechaInput.value);
            if (fechaSeleccionada.getDay() === 0) {
                Swal.fire("Atención", "La SUNAT no publica tipo de cambio los domingos.", "info");
                return;
            }

            // Mostrar loading
            Swal.fire({
                title: "Consultando SUNAT...",
                text: "Por favor espera",
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const response = await fetch(
                    `/admin/mantenimiento/factor/traerSUNAT?date=${fechaInput.value}`
                );

                if (!response.ok) {
                    throw new Error("Error en la conexión con el servidor.");
                }

                const data = await response.json();

                Swal.close(); // cerrar loading antes de mostrar el resultado

                if (data && data.compra && data.venta) {
                    compraInput.value = data.compra;
                    ventaInput.value = data.venta;
                    compraMercado.value = data.compra;
                    ventaMercado.value = data.venta;
                    Swal.fire({
                        icon: "success",
                        title: "Tipo de cambio cargado",
                        html: `
                            <p><b>Compra:</b> ${data.compra}</p>
                            <p><b>Venta:</b> ${data.venta}</p>
                            <p><b>Fecha:</b> ${data.fecha}</p>
                        `
                    });
                } else {
                    Swal.fire("Sin resultados", "No se encontró tipo de cambio para la fecha seleccionada.", "info");
                }
            } catch (error) {
                Swal.close(); // cerrar loading en caso de error
                console.error("Error consultando API SUNAT:", error);
                Swal.fire("Error", "Hubo un problema al consultar el tipo de cambio.", "error");
            }
        });
    }
});
