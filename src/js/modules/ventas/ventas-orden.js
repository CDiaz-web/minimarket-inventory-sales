import Swal from "sweetalert2";
import { verificarTipoCambio } from "./ventas-moneda.js";
import { resetVenta } from "./ventas-articulos.js";

export function initOrdenVenta() {
    const btnGenerar = document.querySelector("#btngenerar");

    if (!btnGenerar) return;

    /**
     * Valida la información necesaria para generar la orden.
     */
    async function validarVenta() {
        const fechaSeleccionada =
            document.getElementById("fecha_venta")?.value;

        const monedaBase = Number(window.APP.config.moneda_base);
        const monedaSeleccionada = Number(App.ventas.moneda);

        // Fecha
        if (!fechaSeleccionada) {
            Swal.fire({
                icon: "warning",
                title: "Fecha requerida",
                text: "Seleccione una fecha para la orden de venta."
            });

            return false;
        }

        // Cliente
        if (!App.ventas.idcliente) {
            Swal.fire({
                icon: "warning",
                title: "Cliente requerido",
                text: "Seleccione un cliente."
            });

            return false;
        }

        // Serie
        if (!App.ventas.idserie) {
            Swal.fire({
                icon: "warning",
                title: "Serie requerida",
                text: "Seleccione una serie documental."
            });

            return false;
        }

        // Lista de precios
        if (!App.ventas.idlista) {
            Swal.fire({
                icon: "warning",
                title: "Lista de precios requerida",
                text: "Seleccione una lista de precios."
            });

            return false;
        }

        // Moneda
        if (!monedaSeleccionada) {
            Swal.fire({
                icon: "warning",
                title: "Moneda requerida",
                text: "Seleccione una moneda."
            });

            return false;
        }

        // Detalle
        if (
            !Array.isArray(App.ventas.articulos) ||
            App.ventas.articulos.length === 0
        ) {
            Swal.fire({
                icon: "warning",
                title: "No hay artículos",
                text: "Agregue al menos un artículo a la orden."
            });

            return false;
        }

        // Validar productos y cantidades
        const detalleInvalido = App.ventas.articulos.some(a => {
            return (
                !Number(a.idproducto) ||
                !Number.isFinite(Number(a.cantidad)) ||
                Number(a.cantidad) <= 0 ||
                !Number.isFinite(Number(a.precio_unitario)) ||
                Number(a.precio_unitario) < 0 ||
                !Number.isFinite(Number(a.precio_unitario_sin_igv)) ||
                Number(a.precio_unitario_sin_igv) < 0 ||
                !Number.isFinite(Number(a.total)) ||
                Number(a.total) < 0 ||
                !Number.isFinite(Number(a.totalsinigv)) ||
                Number(a.totalsinigv) < 0
            );
        });

        if (detalleInvalido) {
            Swal.fire({
                icon: "warning",
                title: "Detalle inválido",
                text: "Revise los productos, las cantidades y los importes."
            });

            return false;
        }

        // Validar totales
        const { subtotal, impuesto, total } = App.ventas.totales;

        if (
            !Number.isFinite(Number(subtotal)) ||
            !Number.isFinite(Number(impuesto)) ||
            !Number.isFinite(Number(total)) ||
            Number(subtotal) < 0 ||
            Number(impuesto) < 0 ||
            Number(total) < 0
        ) {
            Swal.fire({
                icon: "warning",
                title: "Importes inválidos",
                text: "Revise los totales de la orden de venta."
            });

            return false;
        }

        /*
         * Validar tipos de cambio.
         *
         * Siempre necesitamos conservar las cotizaciones reales
         * correspondientes a la fecha seleccionada, independientemente
         * de la moneda del documento.
         */

        const datosTC = await verificarTipoCambio(
            fechaSeleccionada,
            monedaSeleccionada
        );

        if (
            !datosTC.success ||
            !Number.isFinite(Number(datosTC.tc)) ||
            !Number.isFinite(Number(datosTC.tc_oficial)) ||
            Number(datosTC.tc) <= 0 ||
            Number(datosTC.tc_oficial) <= 0
        ) {
            Swal.fire({
                icon: "warning",
                title: "Tipo de cambio no disponible",
                text: datosTC.mensaje ||
                    "No existe un tipo de cambio válido para la fecha seleccionada."
            });

            return false;
        }

        // Conservar las cotizaciones reales de la fecha seleccionada.
        App.ventas.tipoCambio = Number(datosTC.tc);
        App.ventas.tipoCambio_oficial = Number(datosTC.tc_oficial);

        return true;       
        
    }

    /**
     * Evento para generar la orden de venta.
     */
    btnGenerar.addEventListener("click", async function (e) {
        e.preventDefault();

        if (btnGenerar.disabled) return;

        btnGenerar.disabled = true;

        try {
            const validacion = await validarVenta();

            if (!validacion) return;

            await RegistraVenta();

        } finally {
            btnGenerar.disabled = false;
        }
    });

    /**
     * Envía los datos al controlador para registrar la orden.
     */
    async function RegistraVenta() {
        const esEdicion = Boolean(App.ventas.idorden);

        /*
         * La edición conserva su ruta actual.
         * La generación nueva utiliza prp_orden_venta_generar.
         */
        const url = esEdicion
            ? "/admin/gestion/ventas/orden/editar"
            : "/admin/gestion/ventas/orden/generar";

        /*
         * La estructura del JSON respeta los nombres que lee
         * prp_orden_venta_generar.
         */
        const payload = {
            cabecera: {
                idorden: App.ventas.idorden || 0,

                fecha: document.getElementById("fecha_venta")?.value,

                idcliente: Number(App.ventas.idcliente),
                idlista: Number(App.ventas.idlista),
                idserie: Number(App.ventas.idserie),
                idmoneda: Number(App.ventas.moneda),

                observacion:
                    document.getElementById("observacion_venta")?.value || "",

                subtotal: Number(App.ventas.totales.subtotal),
                impuesto: Number(App.ventas.totales.impuesto),
                total: Number(App.ventas.totales.total),

                tipocambio_oficial:
                    Number(App.ventas.tipoCambio_oficial),

                tipocambio:
                    Number(App.ventas.tipoCambio)
            },

            detalle: App.ventas.articulos.map(a => ({
                idproducto: Number(a.idproducto),

                descripcion: String(a.descripcion ?? a.nombre ?? ""),

                cantidad: Number(a.cantidad),

                precio_unitario_sin_igv:
                    Number(a.precio_unitario_sin_igv),

                precio_unitario:
                    Number(a.precio_unitario),

                totalsinigv:
                    Number(a.totalsinigv),

                total:
                    Number(a.total)
            }))
        };

        try {
            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!res.ok || !data.ok) {
                Swal.fire({
                    icon: "error",
                    title: esEdicion
                        ? "No se pudo actualizar la orden"
                        : "No se pudo registrar la orden",
                    text: data.mensaje ||
                        "Ocurrió un error al procesar la solicitud."
                });

                return;
            }

            // El controlador debe devolver el número de la orden.
            const numeroOrden =
                data.numero_formateado ??
                data.numero_documento ??
                data.numero;

            const resultado = await Swal.fire({
                icon: "success",
                title: esEdicion
                    ? `OV ${numeroOrden} actualizada`
                    : `OV ${numeroOrden} generada`,
                text: "¿Desea imprimir la orden de venta?",
                showCancelButton: true,
                confirmButtonText: "Sí, imprimir",
                cancelButtonText: "No"
            });

            if (resultado.isConfirmed) {
                window.open(
                    `/admin/gestion/ventas/orden/imprimir?id=${data.idorden}`,
                    "_blank"
                );
            }

            resetVenta();

        } catch (error) {
            console.error("Error al registrar la orden:", error);

            Swal.fire({
                icon: "error",
                title: "Error",
                text: esEdicion
                    ? "No se pudo actualizar la orden."
                    : "No se pudo registrar la orden."
            });
        }
    }
}