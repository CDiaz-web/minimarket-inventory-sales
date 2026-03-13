export function initTreeview() {

    const tree = document.querySelector('.treeview');
    if (!tree) return;

    tree.addEventListener('change', function (e) {

        if (e.target.type !== 'checkbox') return;

        const checkbox = e.target;

        toggleChildren(checkbox);
        updateParents(checkbox);
    });

    function toggleChildren(checkbox) {
        const li = checkbox.closest('li');
        const children = li.querySelectorAll('ul input[type="checkbox"]');

        children.forEach(child => {
            child.checked = checkbox.checked;
        });
    }

    function updateParents(checkbox) {

        let parentLi = checkbox.closest('ul').parentElement.closest('li');

        while (parentLi) {

            const parentCheckbox = parentLi.querySelector('input[type="checkbox"]');
            const childCheckboxes = parentLi.querySelectorAll('ul > li > input[type="checkbox"]');

            const allChecked = [...childCheckboxes].every(cb => cb.checked);
            const noneChecked = [...childCheckboxes].every(cb => !cb.checked);

            if (allChecked) {
                parentCheckbox.checked = true;
                parentCheckbox.indeterminate = false;
            } 
            else if (noneChecked) {
                parentCheckbox.checked = false;
                parentCheckbox.indeterminate = false;
            } 
            else {
                parentCheckbox.checked = false;
                parentCheckbox.indeterminate = true;
            }

            parentLi = parentLi.closest('ul')?.parentElement?.closest('li');
        }
    }

    // Envío por fetch usando submit del form
    const form = tree.closest('form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const seleccionados = [...tree.querySelectorAll('input[type="checkbox"]:checked')]
            .map(cb => cb.id.replace('cat-', ''));

        const params = new URLSearchParams(location.search);
        const idopcion = params.get('id');

        fetch('/api/guardaropciones', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                seleccionados,
                opcion: idopcion
            })
        })
        .then(() => {
            window.location.href = "/admin/seguridad/perfiles";
        })
        .catch(error => console.error('Error:', error));
    });
}