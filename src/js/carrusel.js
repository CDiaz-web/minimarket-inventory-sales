const scrollContainer = document.querySelector('.categories-container');
const scrollLeftButton = document.querySelector('.scroll-left');
const scrollRightButton = document.querySelector('.scroll-right');

if(scrollContainer){

    // Scroll hacia la izquierda
    scrollLeftButton.addEventListener('click', () => {
        scrollContainer.scrollBy({
            left: -150,  // Ajusta el valor según cuánto quieras desplazarte por clic
            behavior: 'smooth'
        });
    });

    // Scroll hacia la derecha
    scrollRightButton.addEventListener('click', () => {
        scrollContainer.scrollBy({
            left: 150,  // Ajusta el valor según cuánto quieras desplazarte por clic
            behavior: 'smooth'
        });
    });

}