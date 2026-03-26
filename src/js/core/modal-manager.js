class ModalManager {

    constructor() {

        this.modal = document.getElementById('appModal');

        if (!this.modal) {
            console.warn('appModal no encontrado');
            return;
        }
        this.title = this.modal.querySelector('.modal__title');     
        this.body = this.modal.querySelector('.modal__body');
        this.footer = this.modal.querySelector('.modal__footer');
        this.btnClose = this.modal.querySelector('.modal__close');

        this.events();
    }

    events() {
        this.btnClose?.addEventListener('click', () => this.close());

        if (!this.modal) return;
        
        this.modal.addEventListener('click', e => {
            if (e.target === this.modal) this.close();
        });
    }

    showLoader() {
        this.body.innerHTML = `
            <div class="modal__loader">
                Cargando...
            </div>
        `;
    }

    open() {
        this.modal.classList.remove('hidden');
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    setContent({ title='', body='', footer='' }) {

        if(this.title) this.title.innerHTML = title;
        if(this.body) this.body.innerHTML = body;
        if(this.footer) this.footer.innerHTML = footer;
    }

    close() {
        this.modal.classList.remove('active');
        this.modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

export const modal = new ModalManager();