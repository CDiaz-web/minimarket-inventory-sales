import Swal from "sweetalert2";

export function initTipoCambio() {

    const fechaInput = document.getElementById("fecha");
    const btnSunat = document.getElementById("btnTraerSunat");

    if (!btnSunat) return;

    const compraInput = document.getElementById("compra_oficial");
    const ventaInput = document.getElementById("venta_oficial");

    const compraMercado = document.getElementById("compra_mercado");
    const ventaMercado = document.getElementById("venta_mercado");

    

    btnSunat.addEventListener("click", async () => {

        const variacionTC = window.APP.config.variacion_tc;

        if (!fechaInput.value) {
            Swal.fire("Atención", "Primero selecciona una fecha.", "warning");
            return;
        }

        const fechaSeleccionada = new Date(fechaInput.value);

        if (fechaSeleccionada.getDay() === 0) {
            Swal.fire("Atención", "La SUNAT no publica tipo de cambio los domingos.", "info");
            return;
        }

        Swal.fire({
            title: "Consultando SUNAT...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {

            const response = await fetch(
                `/admin/mantenimiento/factor/traerSUNAT?date=${fechaInput.value}`
            );

            if (!response.ok) {
                throw new Error("Error en la conexión");
            }

            const data = await response.json();

            Swal.close();

            if (data && data.compra && data.venta) {

                const compra = parseFloat(data.compra).toFixed(3);
                const venta = parseFloat(data.venta).toFixed(3);

                const compraMerc = (parseFloat(compra) - variacionTC).toFixed(3);
                const ventaMerc = (parseFloat(venta) + variacionTC).toFixed(3);

                compraInput.value = compra;
                ventaInput.value = venta;

                compraMercado.value = compraMerc;
                ventaMercado.value = ventaMerc;

                Swal.fire({
                    icon: "success",
                    title: "Tipo de cambio cargado"
                });

            } else {

                Swal.fire(
                    "Sin resultados",
                    "No se encontró tipo de cambio para la fecha.",
                    "info"
                );

            }

        } catch (error) {

            Swal.close();

            console.error(error);

            Swal.fire(
                "Error",
                "Hubo un problema al consultar el tipo de cambio.",
                "error"
            );

        }

    });

}