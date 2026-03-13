class AlertManager {

    success(message, title = 'Correcto') {
        Swal.fire({
            icon: 'success',
            title,
            text: message,
            confirmButtonText: 'Aceptar'
        });
    }

    error(message, title = 'Error') {
        Swal.fire({
            icon: 'error',
            title,
            text: message,
            confirmButtonText: 'Aceptar'
        });
    }

    warning(message, title = 'Atención') {
        Swal.fire({
            icon: 'warning',
            title,
            text: message,
            confirmButtonText: 'Aceptar'
        });
    }

    info(message, title = 'Información') {
        Swal.fire({
            icon: 'info',
            title,
            text: message,
            confirmButtonText: 'Aceptar'
        });
    }

    async confirm(message, onConfirm, title = 'Confirmar') {

        const result = await Swal.fire({
            icon: 'question',
            title,
            text: message,
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed && typeof onConfirm === 'function') {
            onConfirm();
        }
    }
}

export const alert = new AlertManager();