
export function iniciarAlertas() {

    const alertas = document.querySelectorAll(".alerta");

    alertas.forEach(alerta => {

        setTimeout(() => {
            alerta.classList.add("alerta--hide");

            setTimeout(() => {
                alerta.remove();
            }, 500);

        }, 4000);

    });

}