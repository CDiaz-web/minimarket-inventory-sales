import Swal from "sweetalert2";

export function initSwitchAjax() {

    document.addEventListener('change', async function (e) {

        if (!e.target.classList.contains('js-switch-ajax')) return;

        const checkbox = e.target;

        const id = checkbox.dataset.id;
        const modelo = checkbox.dataset.modelo; 
        const activo = checkbox.checked ? 1 : 0;

        try {

            const response = await fetch('/api/estados', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id, modelo, activo })
            });

            const data = await response.json();

            if (!data.ok) {
                throw new Error(data.mensaje || 'Error al actualizar');
            } else {

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Estado actualizado',
                    showConfirmButton: false,
                    timer: 1500
                });

                const fila = checkbox.closest('tr');

                if (activo === 1) {
                    fila.classList.remove('fila--inactiva');
                } else {
                    fila.classList.add('fila--inactiva');
                }

                fila.classList.add('fila--animando');

                setTimeout(() => {
                    fila.classList.remove('fila--animando');
                }, 600);
            }

        } catch (error) {

            console.error(error);

            checkbox.checked = !checkbox.checked;

            alert('No se pudo actualizar el estado');

        }

    });

}