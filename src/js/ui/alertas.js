
export function iniciarAlertas() {

    const alertas = document.querySelectorAll(".alerta");
    if (!alertas) return;
    alertas.forEach(alerta => {

        setTimeout(() => {
            alerta.classList.add("alerta--hide");

            setTimeout(() => {
                alerta.remove();
            }, 500);

        }, 4000);

    });

}