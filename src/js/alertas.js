
const botonesEliminar = document.querySelectorAll('.table__mantenimiento--eliminar');
const botonSalir = document.querySelector('#cerrar-sesion');
const botonSalir2 = document.querySelector('#cerrar-sesion2');
const botonCargar = document.querySelector('.dashboard__botoncargar');
const imagenCargar = document.querySelector('.formulario__gif');


// Iterar sobre cada botón y agregar un listener
if(botonesEliminar){    
    botonesEliminar.forEach(boton => {
    boton.addEventListener('click', function(e) {
      e.preventDefault();
      const id = this.getAttribute('data-id');
      Swal.fire({
        title: '¿Desea Eliminar el Registro?',
        text: "Ten presente que la operación no es reversible",   
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SI',
        cancelButtonText: 'NO',
        width: '400px'
        }).then((result) => {
          if (result.value) {    
            document.getElementById('frEliminar' + id).submit();
          }   
        })
    });
  });
}

if(botonSalir){
  botonSalir.addEventListener('click', function(e) {
    e.preventDefault();
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
      })
  });
}

if(botonSalir2){
  botonSalir2.addEventListener('click', function(e) {
    e.preventDefault();
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
          document.querySelector('#frSalir2').submit();
        }   
      })
  });
}


if(botonCargar){
  botonCargar.addEventListener('click', function(e) {
    e.preventDefault();
    const valida = document.getElementById('fileProductos').value

    if(valida===''){

      Swal.fire({
        position:'center',
        icon:'warning',
        title: 'Debe seleccionar un archivo Excel',
        showConfirmButton:false,  
        width: '400px',
        timer: 2500,   
        })      
    }else{
      
      botonCargar.classList.remove('dashboard__botoncargar')
      botonCargar.classList.add('dashboard__botoncargar--deshabilitada')
      imagenCargar.classList.remove('formulario__gif')
      imagenCargar.classList.add('formulario__gif--visible')

      document.querySelector('#form_carga').submit();
      
      // botonCargar.classList.remove('dashboard__botoncargar--deshabilitada')
      // botonCargar.classList.add('dashboard__botoncargar')
      // imagenCargar.classList.remove('formulario__gif--visible')
      // imagenCargar.classList.add('formulario__gif')
    }
  });
  
}
