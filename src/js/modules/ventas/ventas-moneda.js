import Swal from "sweetalert2";
import { recalcularPreciosVenta } from "./ventas-articulos.js";

/**
 * Inicializa el selector de moneda de la orden de venta.
 */
export function initVentaMoneda() {
    const selectMoneda = document.getElementById("idmoneda");

    if (!selectMoneda) return;

    selectMoneda.addEventListener("change", async function () {
        const monedaSeleccionada = Number(this.value);
        const monedaBase = Number(window.APP.config.moneda_base);
        const fecha = document.getElementById("fecha_venta")?.value;

        const monedaAnterior = Number(App.ventas.moneda);

        if (!monedaSeleccionada) {
            Swal.fire({
                icon: "warning",
                title: "Moneda requerida",
                text: "Seleccione una moneda para la orden de venta."
            });

            return;
        }

        if (!fecha) {
            this.value = monedaAnterior || monedaBase;

            Swal.fire({
                icon: "warning",
                title: "Fecha requerida",
                text: "Seleccione la fecha de la orden de venta."
            });

            return;
        }

        try {
            const datosTC = await verificarTipoCambio(
                fecha,
                monedaSeleccionada
            );

            if (!esTipoCambioValido(datosTC)) {
                this.value = monedaAnterior || monedaBase;

                Swal.fire({
                    icon: "warning",
                    title: "Tipo de cambio no disponible",
                    text: datosTC.mensaje ||
                        "No existe un tipo de cambio válido para la fecha seleccionada."
                });

                return;
            }

            // Actualizar moneda y tipos de cambio.
            App.ventas.moneda = monedaSeleccionada;
            App.ventas.tipoCambio = Number(datosTC.tc);
            App.ventas.tipoCambio_oficial = Number(datosTC.tc_oficial);

            actualizarSimbolosMoneda(
                monedaSeleccionada,
                monedaBase
            );

            actualizarCampoTipoCambio(App.ventas.tipoCambio);

            // Recalcular precios comerciales.
            recalcularPreciosVenta();

        } catch (error) {
            console.error("Error al cambiar la moneda:", error);

            this.value = monedaAnterior || monedaBase;

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudo consultar el tipo de cambio."
            });
        }
    });
}

/**
 * Inicializa el control de fecha de la orden de venta.
 */
export function initFechaVenta() {
    const inputFecha = document.getElementById("fecha_venta");

    if (!inputFecha) return;

    // Consultar las cotizaciones de la fecha inicial.
    actualizarTipoCambio();

    inputFecha.addEventListener("change", async function () {
        const fecha = this.value;
        const moneda = Number(
            document.getElementById("idmoneda")?.value
        );

        if (!fecha) {
            App.ventas.tipoCambio = null;
            App.ventas.tipoCambio_oficial = null;

            actualizarCampoTipoCambio(null);
            return;
        }

        try {
            const datosTC = await verificarTipoCambio(fecha, moneda);

            if (!esTipoCambioValido(datosTC)) {
                App.ventas.tipoCambio = null;
                App.ventas.tipoCambio_oficial = null;

                actualizarCampoTipoCambio(null);

                Swal.fire({
                    icon: "warning",
                    title: "Tipo de cambio no disponible",
                    text: datosTC.mensaje ||
                        "No existe un tipo de cambio válido para la fecha seleccionada."
                });

                return;
            }

            App.ventas.tipoCambio = Number(datosTC.tc);
            App.ventas.tipoCambio_oficial = Number(datosTC.tc_oficial);

            actualizarCampoTipoCambio(App.ventas.tipoCambio);

            // Actualizar los precios según el TC comercial.
            recalcularPreciosVenta();

        } catch (error) {
            console.error("Error al cambiar la fecha:", error);

            App.ventas.tipoCambio = null;
            App.ventas.tipoCambio_oficial = null;

            actualizarCampoTipoCambio(null);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "No se pudieron consultar los tipos de cambio."
            });
        }
    });
}

/**
 * Consulta el tipo de cambio para una fecha y moneda.
 */
export async function verificarTipoCambio(fecha, idMoneda) {
    try {
        const formData = new FormData();

        formData.append("fecha", fecha);
        formData.append("idMoneda", idMoneda);

        const response = await fetch(
            "/admin/gestion/ventas/orden/validarTipoCambio",
            {
                method: "POST",
                body: formData
            }
        );

        const data = await response.json();

        if (!response.ok) {
            return {
                success: false,
                mensaje: data.mensaje ||
                    "No se pudo consultar el tipo de cambio."
            };
        }

        return data;

    } catch (error) {
        console.error("Error al consultar el tipo de cambio:", error);

        return {
            success: false,
            mensaje: "No se pudo consultar el tipo de cambio."
        };
    }
}

/**
 * Actualiza los tipos de cambio correspondientes a la fecha y moneda actuales.
 */
export async function actualizarTipoCambio() {
    const inputFecha = document.getElementById("fecha_venta");
    const selectMoneda = document.getElementById("idmoneda");

    if (!inputFecha || !selectMoneda) return;

    const fecha = inputFecha.value;
    const moneda = Number(selectMoneda.value);

    if (!fecha || !moneda) {
        App.ventas.tipoCambio = null;
        App.ventas.tipoCambio_oficial = null;

        actualizarCampoTipoCambio(null);
        return;
    }

    try {
        const datosTC = await verificarTipoCambio(fecha, moneda);

        if (!esTipoCambioValido(datosTC)) {
            App.ventas.tipoCambio = null;
            App.ventas.tipoCambio_oficial = null;

            actualizarCampoTipoCambio(null);
            return;
        }

        App.ventas.tipoCambio = Number(datosTC.tc);
        App.ventas.tipoCambio_oficial = Number(datosTC.tc_oficial);

        actualizarCampoTipoCambio(App.ventas.tipoCambio);

    } catch (error) {
        console.error("Error al inicializar los tipos de cambio:", error);

        App.ventas.tipoCambio = null;
        App.ventas.tipoCambio_oficial = null;

        actualizarCampoTipoCambio(null);
    }
}

/**
 * Valida que la respuesta contenga ambos tipos de cambio.
 */
function esTipoCambioValido(datosTC) {
    return Boolean(
        datosTC?.success &&
        Number.isFinite(Number(datosTC.tc)) &&
        Number.isFinite(Number(datosTC.tc_oficial)) &&
        Number(datosTC.tc) > 0 &&
        Number(datosTC.tc_oficial) > 0
    );
}

/**
 * Actualiza los símbolos de moneda de los totales.
 */
function actualizarSimbolosMoneda(monedaSeleccionada, monedaBase) {
    const simbolos = [
        document.getElementById("simboloMoneda01"),
        document.getElementById("simboloMoneda02"),
        document.getElementById("simboloMoneda03")
    ];

    // Conservamos los símbolos actuales del formulario.
    const simbolo = monedaSeleccionada === monedaBase ? "$" : "S/";

    simbolos.forEach(elemento => {
        if (elemento) {
            elemento.textContent = simbolo;
        }
    });
}

/**
 * Actualiza el campo visual del tipo de cambio.
 */
function actualizarCampoTipoCambio(tipoCambio) {
    const inputTC = document.getElementById("tipoCambio_venta");

    if (!inputTC) return;

    if (
        tipoCambio === null ||
        tipoCambio === undefined ||
        !Number.isFinite(Number(tipoCambio)) ||
        Number(tipoCambio) <= 0
    ) {
        inputTC.value = "--";
        return;
    }

    inputTC.value = Number(tipoCambio).toFixed(3);
}