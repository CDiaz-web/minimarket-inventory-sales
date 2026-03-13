export function initUpload() {

    const form = document.querySelector('[data-upload]');
    if (!form) return;

    const fileInput = form.querySelector('#fileProductos');
    const fileName = form.querySelector('#fileName');
    const loading = form.querySelector('.formulario__loading');
    const button = form.querySelector('#btnCargar');

    fileInput.addEventListener('change', () => {
        fileName.textContent = fileInput.files.length 
            ? fileInput.files[0].name 
            : 'Ningún archivo seleccionado';
    });

    form.addEventListener('submit', (e) => {

        if (!fileInput.files.length) {
            e.preventDefault();
            App.alert.error('Debe seleccionar un archivo');
            return;
        }

        loading.hidden = false;
        button.disabled = true;
        button.textContent = 'Procesando...';
    });
}