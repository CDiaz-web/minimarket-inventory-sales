const tipoPersona = document.getElementById("tipo_persona");

if (tipoPersona) {
    document.addEventListener("DOMContentLoaded", function () {
        
        const nombreField = document.getElementById("nombre").closest(".formulario__campo");
        const apellidosField = document.getElementById("apellidos").closest(".formulario__campo");
        const razonSocialField = document.getElementById("razon_social").closest(".formulario__campo");
        const documento = document.getElementById("documento");

        function toggleCampos() {
            if (tipoPersona.value === "N") {
                nombreField.classList.remove("oculto");
                apellidosField.classList.remove("oculto");
                razonSocialField.classList.add("oculto");
                // razonSocialField.value="";
                documento.placeholder = "DNI";
            } else if (tipoPersona.value === "J") {
                nombreField.classList.add("oculto");
                apellidosField.classList.add("oculto");
                razonSocialField.classList.remove("oculto");
                // nombreField.value="";
                // apellidosField.value="";
                documento.placeholder = "RUC";
            } else {
                nombreField.classList.add("oculto");
                apellidosField.classList.add("oculto");
                razonSocialField.classList.add("oculto");
                documento.placeholder = "RUC/DNI";
            }
        }

        // Evento cuando cambia el select
        tipoPersona.addEventListener("change", toggleCampos);

        // Ejecutar una vez al cargar (para editar cliente existente)
        toggleCampos();
    });
}



