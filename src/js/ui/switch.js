export function initSwitchEstado() {

    const switches = document.querySelectorAll('[data-switch-estado]');

    if (!switches.length) return;

    switches.forEach(container => {

        const checkbox = container.querySelector('input[type="checkbox"]');
        const texto = container.querySelector('[data-switch-label]');

        if (!checkbox || !texto) return;

        // Estado inicial (por si acaso)
        texto.textContent = checkbox.checked ? 'Activo' : 'Inactivo';

        checkbox.addEventListener('change', function () {

            const activo = this.checked;

            // Cambiar texto
            texto.textContent = activo ? 'Activo' : 'Inactivo';

            // Cambiar clases (opcional)
            texto.classList.toggle('activo', activo);
            texto.classList.toggle('inactivo', !activo);

        });

    });

}

