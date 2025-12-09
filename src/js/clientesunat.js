const btnBuscar = document.getElementById("btnTraerCliente");
if (btnBuscar) {
    document.addEventListener("DOMContentLoaded", function () {
        // const btnBuscar = document.getElementById("btnTraerCliente");
        const inputDoc = document.getElementById("documento");

        btnBuscar.addEventListener("click", async function () {
            const documento = inputDoc.value.trim();
            if (!documento) { alert("Ingrese un DNI o RUC primero"); return; }

            let tipo = "";
            if (/^\d{8}$/.test(documento)) tipo = "dni";
            else if (/^\d{11}$/.test(documento)) tipo = "ruc";
            else { alert("El documento debe tener 8 dígitos (DNI) o 11 dígitos (RUC)"); return; }

            // mostrar loader con SweetAlert (si usas)
            Swal.fire({ title: "Consultando...", allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            try {
                //const response = await fetch(`/admin/mantenimiento/clientes/traerDocumento?tipo=${tipo}&numero=${documento}`);
                const response = await fetch(`/admin/mantenimiento/clientes/clientes/traerDocumento?tipo=${tipo}&numero=${documento}`);
                const data = await response.json();
                Swal.close();

                if (data.error) { alert(data.error); return; }

                if (tipo === "dni") {
                    document.getElementById("nombre").value = data.nombres || "";
                    document.getElementById("apellidos").value = `${data.apellidoPaterno || ""} ${data.apellidoMaterno || ""}`.trim();
                    document.getElementById("tipo_persona").value = "N";
                    document.getElementById("razon_social").value = "";
                } else if (tipo === "ruc") {
                    if (data.tipo === "J") {
                        document.getElementById("razon_social").value = data.razonSocial || "";
                        document.getElementById("tipo_persona").value = "J";
                        // limpiar campos de natural
                        document.getElementById("nombre").value = "";
                        document.getElementById("apellidos").value = "";
                    } else {
                        // persona natural detectada (por heurística del backend)
                        document.getElementById("nombre").value = data.nombres || "";
                        document.getElementById("apellidos").value = `${data.apellidoPaterno || ""} ${data.apellidoMaterno || ""}`.trim();
                        document.getElementById("tipo_persona").value = "N";
                        // limpiar razon social
                        document.getElementById("razon_social").value = data.razonSocial || "";
                    }
                }

                if (data.direccion) {
                    document.getElementById("direccion").value = data.direccion;
                }

                Swal.fire({ icon: "success", title: "Datos cargados" });

            } catch (error) {
                Swal.close();
                console.error("Error consultando documento:", error);
                alert("Hubo un problema al consultar el documento");
            }
        });
    });


}


