(function(){
    const año = document.querySelector('#año');
    añoInput.addEventListener('input',function(e){
        const añoSeleccionado = e.target.value;

        window.location = `?año=${añoSeleccionado}`
    });
})();