import { modal } from './modal-manager.js';

export function initModalLoader() {

    document.addEventListener('click', async e => {

        // FIX
        if (e.target.closest('.modal')) return;
        if (e.target.closest('.swal2-container')) return;
        if (e.defaultPrevented) return;
        const trigger = e.target.closest('[data-modal]:not([data-modal="card"])');
        if (!trigger) return;
        
        e.preventDefault();

        const url = trigger.dataset.modal;
        
        if (!modal.modal) return;

        if (!url) return;
        
        modal.open();
        modal.showLoader();

        try {

            const data = await App.http.get(`/modal/${url}`);

            modal.setContent({
                title: data.title,
                body: data.body,
                footer: data.footer
            });

        } catch (error) {

            modal.setContent({
                title: 'Error',
                body: 'No se pudo cargar el contenido'
            });

        }

    });
}