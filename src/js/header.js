const mobileMenuBtn = document.querySelector('#mobile-menu');

const cerrarMenuBtn = document.querySelector('.cerrar-menu');
const sidebar = document.querySelector('.dashboard__sidebar');

if(mobileMenuBtn){
   
    mobileMenuBtn.addEventListener('click',function(){
  
        sidebar.classList.remove('dashboard__sidebar');
        sidebar.classList.add('mostrar');
    })
}

if(cerrarMenuBtn){
    const sidebarX = document.querySelector('.mostrar');
    cerrarMenuBtn.addEventListener('click',function(){              
        
        sidebar.classList.remove('mostrar');
        sidebar.classList.add('dashboard__sidebar');

    })
}

//elimina la clase de mostrar en un tamañod e tablet y mayores
const anchoPantalla = document.body.clientWidth;

window.addEventListener('resize', function() {
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768) {
        sidebar.classList.remove('mostrar');
        sidebar.classList.add('dashboard__sidebar');
    }
})
