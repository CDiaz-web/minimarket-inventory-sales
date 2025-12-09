

if(document.querySelector('#treeview-container')){

    document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Si se marca el checkbox, se marcan todos los hijos
            if (this.checked) {
                marcarHijos(this);
            } else {
                // Si se desmarca el checkbox, se desmarcan los hijos, pero no se toca el padre
                desmarcarHijos(this);
            }
        });
    });
    
    function marcarHijos(checkbox) {
        let hijos = checkbox.closest('li').querySelectorAll('input[type="checkbox"]');
        hijos.forEach(function(hijo) {
            hijo.checked = true;
        });
    }
    
    function desmarcarHijos(checkbox) {
        let hijos = checkbox.closest('li').querySelectorAll('input[type="checkbox"]');
        hijos.forEach(function(hijo) {
            hijo.checked = false;
        });
    }
    
    function obtenerIdsSeleccionados() {
        // Seleccionar todos los checkboxes dentro del treeview
        const checkboxes = document.querySelectorAll('.treeview__ul input[type="checkbox"]');
        let idsSeleccionados = [];       
        // Recorrer los checkboxes y verificar cuáles están marcados
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {                 
                idsSeleccionados.push(checkbox.id); // Agregar el id del checkbox marcado
            }
        });
    
        // Retornar los IDs seleccionados
        return idsSeleccionados;
    }

 
    document.querySelector('#guardarSeleccionados').addEventListener('click', function() {          
        const seleccionados = obtenerIdsSeleccionados();    
        let params = new URLSearchParams(location.search);
        var idopcion = params.get('id');        
     
       guardarSeleccionadosEnServidor(seleccionados,idopcion); 
    });

    function guardarSeleccionadosEnServidor(idsSeleccionados,idopcion) {
        
        fetch('/api/guardaropciones', {            
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                seleccionados: idsSeleccionados,
                opcion:idopcion
            })
        })
        .then(response => response.text())
        .then(data => {
            window.location.href = "/admin/seguridad/perfiles";
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

}



