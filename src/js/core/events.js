import Swal from 'sweetalert2';
import { exportarTablaXLSX } from '../ui/export.js';
import { initTreeview } from '../modules/treeview.js';
import { initUpload } from '../modules/upload.js';

export const events = {

    init() {
        document.addEventListener('click', this.handleClick.bind(this));
        document.addEventListener('submit', this.handleSubmit.bind(this));
         initTreeview();
         initUpload();
    },

    // ======================
    // CLICK EVENTS
    // ======================
    async handleClick(e) {

        // const el = e.target.closest('[data-action]');
        const el = e.target?.closest?.('[data-action]');
        if (!el) return;

        const action = el.dataset.action;

        if (!action || typeof this[action] !== 'function') {
            return;
        }

        // ⚠️ prevenir SOLO cuando realmente manejamos la acción
        if (el.matches('a, button, input[type="submit"]')) {
            e.preventDefault();
        }

        const confirmMessage = el.dataset.confirm;

        // Si requiere confirmación
        if (confirmMessage) {

            let confirmed = false;

            await App.alert.confirm(
                confirmMessage,
                () => confirmed = true
            );

            if (!confirmed) return;
        }

        this[action](el, e);
    },



    exportTable(el) {

        const idTabla = el.dataset.table;
        const nombreArchivo = el.dataset.file || "export.xlsx";
        const nombreHoja = el.dataset.sheet || "Hoja1";

        exportarTablaXLSX(idTabla, nombreArchivo, nombreHoja);
    },


    // ======================
    // SUBMIT EVENTS
    // ======================
    async handleSubmit(e) {

        const form = e.target.closest('[data-ajax]');
        if (!form) return;

        e.preventDefault();

        try {

            const formData = new FormData(form);

            const response = await App.http.post(
                form.action,
                Object.fromEntries(formData)
            );

        App.alert.success(response.message || 'Correcto');

        if (response.redirect) {
            window.location.href = response.redirect;
        }

        } catch (error) {
            console.error(error);
        }
    },
    // ======================
    // ACTIONS 
    // ======================

    deleteUser(el) {

        const id = el.dataset.id;

        console.log('Eliminar usuario', id);

    },
    deleteRecord(el) {

        const id = el.dataset.id;

        Swal.fire({
            title: '¿Desea eliminar el registro?',
            text: 'Ten presente que la operación no es reversible',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI',
            cancelButtonText: 'NO',
            width: '400px'
        }).then(result => {

        if (result.isConfirmed) {
                    document
                        .getElementById(`frEliminar${id}`)
                        ?.submit();
                }

            });
    },
    cerraSesion() {

	    Swal.fire({
	      title: '¿Desea Cerrar Sesion?',
	      text: "",   
	      showCancelButton: true,
	      confirmButtonColor: '#3085d6',
	      cancelButtonColor: '#d33',
	      confirmButtonText: 'SI',
	      cancelButtonText: 'NO',
	      width: '400px'
	      }).then((result) => {
		if (result.value) {
		  document.querySelector('#frSalir').submit();
		}   
	      });
    }
    
};
