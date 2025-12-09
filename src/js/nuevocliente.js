(function(){

   
    const btnCliente= document.querySelector('#btnnuevo');    
 

    let tipospersona=[];   
    let tiposidentidad=[];   

    async function obtenerTiposPersona(){
        const url = `/api/tipopersona`;             
        const respuesta = await fetch(url);           
        const resultado = await respuesta.json();              
        formatearTiposPersona(resultado)
      
    }


    function formatearTiposPersona(arrayTiposPersona =[]){
        tipospersona = arrayTiposPersona.map( tpersona => {
            return{
                id: tpersona.id,
                nombre:tpersona.nombre                   
            }
        })          
    }
  
   
    async function obtenerTiposIdentidad(){
        const url = `/api/tipoidentidad`;             
        const respuesta = await fetch(url);           
        const resultado = await respuesta.json();              
        formatearTiposIdentidad(resultado)
      
    }

    function formatearTiposIdentidad(arrayTiposIdentidad =[]){
        tiposidentidad = arrayTiposIdentidad.map( tidentidad => {
            return{
                id: tidentidad.id,
                nombre:tidentidad.nombre                   
            }
        })          
    }


    // Obtener el importe total desde el span en la página principal


    if(btnCliente){

        obtenerTiposPersona();
        obtenerTiposIdentidad();
        // console.log(tipospago);
        btnCliente.addEventListener('click', async function(){    
            mostraFormulario();            
        }); 

        function mostraFormulario(){    
            
            const modal = document.createElement('DIV');  
            modal.classList.add('modal');

            modal.innerHTML = `
            <form class="formulario nueva-tarea">                
            <legend>Registrar Cliente</legend>

                
                <fieldset class="formulario__fieldset">

                    <div class="formulario__campo">
                        <label for="idtipo_persona" class="formulario__label">Tipo de Persona</label>
                        <select class="formulario__select" id="idtipo_persona" name="idtipo_persona">
                            <option value="">-Seleccionar-</option>
                        </select>
                    </div>   
                    
                    <div class="formulario__campo">
                        <label for="idtipo_identidad" class="formulario__label">Tipo Documento</label>
                        <select class="formulario__select" id="idtipo_identidad" name="idtipo_identidad">
                            <option value="">-Seleccionar-</option>
                        </select>
                    </div>                    
     

                    
                    <div class="formulario__campo">    
                        <label for="numero" class="formulario__label">Documento</label>
                        <input
                            type = "text"
                            class = "formulario__input"
                            id = "numero"
                            name="numero"
                            placeholder = "Número de Documento"
                        />
                    </div> 
                    
                    <div class="formulario__campo">    
                        <label for="nombre" class="formulario__label">Nombre Cliente</label>
                        <input
                            type = "text"
                            class = "formulario__input"
                            id = "nombre"
                            name="nombre"
                            placeholder = "Nombre del Cliente"
                        />
                    </div>  

                    <div class="formulario__campo">    
                        <label for="nombre" class="formulario__label">Direccion</label>
                        <input
                            type = "text"
                            class = "formulario__input"
                            id = "direccion"
                            name="direccion"
                            placeholder = "Direccion del Cliente"
                        />
                    </div>  

                </fieldset>                       
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="Registrar"/>
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
            `;  
           
                   
                
            setTimeout(() => {
                const formulario = document.querySelector('.formulario');
                 formulario.classList.add('animar');
            }, 0);
            
            modal.addEventListener('click', function(e){
                e.preventDefault();
                //boton cerrar    
                if(e.target.classList.contains('cerrar-modal')){
                    const formulario = document.querySelector('.formulario');
                    formulario.classList.add('cerrar');                   
                    setTimeout(() => {
                        modal.remove();
                    }, 500);        
                }


            //boton nueva clietne
            if(e.target.classList.contains('submit-nueva-tarea')){
                const nombreCliente = document.querySelector('#nombre').value.trim();
                const numeroCliente = document.querySelector('#numero').value.trim();
                const direccCliente = document.querySelector('#direccion').value.trim();
                const tipoCliente = document.querySelector('#idtipo_persona').value.trim();
                const identiCliente = document.querySelector('#idtipo_identidad').value.trim();
                if(tipoCliente === ''){
                    //mostrar alerta de error
                    mostrarAlerta('El Tipo de Cliente es obligatorio','alerta__error',document.querySelector('.formulario legend'));            
                    return;
                }  
                if(identiCliente === ''){
                    //mostrar alerta de error
                    mostrarAlerta('El Tipo de Documento es obligatorio','alerta__error',document.querySelector('.formulario legend'));            
                    return;
                }  
                if(numeroCliente === ''){
                    //mostrar alerta de error
                    mostrarAlerta('El número de Documento es obligatorio','alerta__error',document.querySelector('.formulario legend'));            
                    return;
                }  
                if(nombreCliente === ''){
                    //mostrar alerta de error
                    mostrarAlerta('El nombre del Cliente es obligatorio','alerta__error',document.querySelector('.formulario legend'));            
                    return;
                }  

                if(direccCliente === ''){
                    //mostrar alerta de error
                    mostrarAlerta('La Dirección es obligatorio','alerta__error',document.querySelector('.formulario legend'));            
                    return;
                }  
            
                agregarCliente(tipoCliente,identiCliente,numeroCliente,nombreCliente,direccCliente);
            
            }


            })

            document.querySelector('.dashboard').appendChild(modal); 

            // Llenar el select después de que el modal se ha insertado en el DOM
            let selectPersona = modal.querySelector("#idtipo_persona");

            tipospersona.forEach(tipopersona => {
                let option = document.createElement("option");
                option.value = tipopersona.id;
                option.textContent = tipopersona.nombre;                
            
                selectPersona.appendChild(option);
            });
            
            let selectIdentidad = modal.querySelector("#idtipo_identidad");
            
            tiposidentidad.forEach(tipoidentidad => {
                let option = document.createElement("option");
                option.value = tipoidentidad.id;
                option.textContent = tipoidentidad.nombre;                
            
                selectIdentidad.appendChild(option);
            });

        }
        
    }
        
    //muestra un mensjae en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia){
        //previene la creacio de multiuples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }
        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta',tipo);
        alerta.textContent = mensaje;
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        //eliminar la alerta luego de 5 segundos
        setTimeout(() => {
            alerta.remove();
        }, 5000);

    }


    async function agregarCliente(persona,identidad,numero,nombre,direccion){
        //construir la peticion
        const datos = new FormData();
        datos.append('idtipo_persona',persona);  
        datos.append('idtipo_identidad',identidad);  
        datos.append('idtipo_entidad','1'); 
        datos.append('numero',numero); 
        datos.append('nombre',nombre);   
        datos.append('direccion',direccion);    

        try {
            const url = 'http://localhost:3000/api/entidades/crearcliente';
            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();            

            mostrarAlerta(
                resultado.mensaje, 
                resultado.tipo, 
                document.querySelector('.formulario legend')
            ); 
            
            if(resultado.tipo === 'alerta__exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();   
                  
                }, 3000);   
               
              
            }
        
        } catch (error) {
            console.log(error);
        }
    }


  
})();
