document.addEventListener('DOMContentLoaded', function () {
    const dropdowns = document.querySelectorAll('.dropdown-btn');

    dropdowns.forEach(function(dropdown) {
        dropdown.addEventListener('click', function () {
            const dropdownContent = this.nextElementSibling;
            // Cerrar todos los menús desplegables abiertos
            document.querySelectorAll('.dropdown-container').forEach(function (container) {
                if (container !== dropdownContent) {
                    container.style.height = '0';
                    setTimeout(() => {
                        container.style.display = 'none';
                    }, 500);
                }
            });
            if (dropdownContent) {
                if (dropdownContent.style.display === 'flex') {
                    dropdownContent.style.height = '0';
                    setTimeout(() => {
                        dropdownContent.style.display = 'none';
                    }, 500);  //Debe coincidir con la duración de la transición en el CSS
                } else {
                    dropdownContent.style.display = dropdownContent.style.display === 'flex' ? 'none' : 'flex';
                    //dropdownContent.style.display = 'block';
                    dropdownContent.style.height = dropdownContent.scrollHeight + 'px';
                }
            }

        });
    });

});