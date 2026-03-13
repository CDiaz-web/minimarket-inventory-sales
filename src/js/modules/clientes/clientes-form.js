import Swal from "sweetalert2";
export function initClientesForm() {

    const tipoPersona = document.getElementById("tipo_persona");
    if (!tipoPersona) return;

    const nombreField = document.getElementById("nombre")?.closest(".formulario__campo");
    const apellidosField = document.getElementById("apellidos")?.closest(".formulario__campo");
    const razonSocialField = document.getElementById("razon_social")?.closest(".formulario__campo");

    const documentoInput = document.getElementById("documento");
    const btnBuscar = document.getElementById("btnTraerCliente");

    /* =============================
       MOSTRAR / OCULTAR CAMPOS
    ============================= */

    function toggleCampos() {

        if (tipoPersona.value === "N") {

            nombreField?.classList.remove("oculto");
            apellidosField?.classList.remove("oculto");
            razonSocialField?.classList.add("oculto");

            documentoInput.placeholder = "DNI";

        } else if (tipoPersona.value === "J") {

            nombreField?.classList.add("oculto");
            apellidosField?.classList.add("oculto");
            razonSocialField?.classList.remove("oculto");

            documentoInput.placeholder = "RUC";

        } else {

            nombreField?.classList.add("oculto");
            apellidosField?.classList.add("oculto");
            razonSocialField?.classList.add("oculto");

            documentoInput.placeholder = "RUC/DNI";
        }
    }

    tipoPersona.addEventListener("change", toggleCampos);
    toggleCampos();


    /* =============================
       CONSULTAR DNI / RUC
    ============================= */

    if (!btnBuscar) return;

    btnBuscar.addEventListener("click", async () => {

        const documento = documentoInput.value.trim();

        if (!documento) {
            alert("Ingrese un DNI o RUC primero");
            return;
        }

        let tipo = "";

        if (/^\d{8}$/.test(documento)) tipo = "dni";
        else if (/^\d{11}$/.test(documento)) tipo = "ruc";
        else {
            alert("El documento debe tener 8 dígitos (DNI) o 11 dígitos (RUC)");
            return;
        }

        Swal.fire({
            title: "Consultando...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {

            const response = await fetch(`/admin/mantenimiento/clientes/clientes/traerDocumento?tipo=${tipo}&numero=${documento}`);

            const data = await response.json();

            Swal.close();

            if (data.error) {
                alert(data.error);
                return;
            }

            if (tipo === "dni") {

                document.getElementById("nombre").value = data.nombres || "";
                document.getElementById("apellidos").value =
                    `${data.apellidoPaterno || ""} ${data.apellidoMaterno || ""}`.trim();

                tipoPersona.value = "N";

                document.getElementById("razon_social").value = "";

            }

            if (tipo === "ruc") {

                if (data.tipo === "J") {

                    document.getElementById("razon_social").value = data.razonSocial || "";
                    tipoPersona.value = "J";

                    document.getElementById("nombre").value = "";
                    document.getElementById("apellidos").value = "";

                } else {

                    document.getElementById("nombre").value = data.nombres || "";

                    document.getElementById("apellidos").value =
                        `${data.apellidoPaterno || ""} ${data.apellidoMaterno || ""}`.trim();

                    tipoPersona.value = "N";

                    document.getElementById("razon_social").value = data.razonSocial || "";
                }
            }

            if (data.direccion) {
                document.getElementById("direccion").value = data.direccion;
            }

            toggleCampos();

            Swal.fire({
                icon: "success",
                title: "Datos cargados"
            });

        } catch (error) {

            Swal.close();

            console.error("Error consultando documento:", error);

            alert("Hubo un problema al consultar el documento");
        }

    });

}