(function(){

    const btnCajaMovi = document.querySelector('#caja-movi');    
    const btnCajaSali = document.querySelector('#caja-salida');    
    if(btnCajaMovi){

        btnCajaMovi.addEventListener('click', function(){                     
            mostraFormulario(false);            
        }); 

        function mostraFormulario(cerrar = false){    
            
            const modal = document.createElement('DIV');  
            modal.classList.add('modal');
            if (cerrar){
                modal.innerHTML = `
                <form class="formulario nueva-tarea">                
                <legend>Aperturar Caja</legend>

                   
                    <fieldset class="formulario__fieldset">
               
                        <div class="formulario__campo">    
                            <label for="actual" class="formulario__label">Saldo Actual en caja</label>
                            <input
                                type = "numeric"
                                class = "formulario__input"
                                id = "actual"
                                name="actual"
                                placeholder="Saldo Actual" 
                            />
                        </div>                        
                        
                        <div class="formulario__campo">    
                            <label for="inicial" class="formulario__label">Saldo Inicial</label>
                            <input
                                type = "numeric"
                                class = "formulario__input"
                                id = "inicial"
                                name="inicial"
                                placeholder="Saldo Inicial" 
                            />
                        </div>   

                    </fieldset>                       
                    <div class="opciones">
                        <input type="submit" class="submit-nueva-tarea" value="Aperturar"/>
                        <button type="button" class="cerrar-modal">Cancelar</button>
                    </div>
                </form>
                `;  
            }else{
                modal.innerHTML = `
                <form class="formulario nueva-tarea">                
                <legend>Cerrar Caja</legend>

                   
                    <fieldset class="formulario__fieldset">
               
                        <div class="formulario__campo">    
                            <label for="ventas" class="formulario__label">Total Ventas</label>
                            <input
                                type = "numeric"
                                class = "formulario__input"
                                id = "ventas"
                                name="ventas"
                                placeholder="Total Ventas" 
                            />
                        </div>                        
                        
                        <div class="formulario__campo">    
                            <label for="saldo" class="formulario__label">Saldo Fisico</label>
                            <input
                                type = "numeric"
                                class = "formulario__input"
                                id = "saldo"
                                name="saldo"
                                placeholder="Saldo Inicial" 
                            />
                        </div>   
                        <div class="formulario__campo">    
                            <label for="observacion" class="formulario__label">Observaciones</label>
                            <textarea 
                                id="observacion" 
                                name ="observacion">
                            </textarea> 
                        </div>   

                    </fieldset>                       
                    <div class="opciones">
                        <input type="submit" class="submit-nueva-tarea" value="Aperturar"/>
                        <button type="button" class="cerrar-modal">Cancelar</button>
                    </div>
                </form>
                `;                  
            }
                   
                
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
                //boton nueva tarea
                // if(e.target.classList.contains('submit-nueva-tarea')){
                //     const nombreTarea = document.querySelector('#tarea').value.trim();
                //     if(nombreTarea === ''){
                //         //mostrar alerta de error
                //         mostrarAlerta('El nombre de la tarea es obligatoriio','error',document.querySelector('.formulario legend'));            
                //         return;
                //     }  
             
                //     if(editar){                        
                //         tarea.nombre = nombreTarea;
                //         actualizarTarea(tarea);
                //     }else{
                //         agregarTarea(nombreTarea);
                //     }
                // }
    
            })

            document.querySelector('.dashboard').appendChild(modal); 
        }
        
    }
    

    if(btnCajaSali){

        btnCajaSali.addEventListener('click', function(){                     
            mostraFormulario2();            
        }); 

        function mostraFormulario2(){    
            
            const modal = document.createElement('DIV');  
            modal.classList.add('modal');
       
            modal.innerHTML = `
            <form class="formulario nueva-tarea">                
            <legend>Registrar Salida</legend>

                
                <fieldset class="formulario__fieldset">
            
                    <div class="formulario__campo">    
                        <label for="motivo" class="formulario__label">Motivo</label>
                        <input
                            type = "text"
                            class = "formulario__input"
                            id = "motivo"
                            name="motivo"
                            placeholder="Motivo" 
                        />
                    </div>                        
                    
                    <div class="formulario__campo">    
                        <label for="monto" class="formulario__label">Monto</label>
                        <input
                            type = "numeric"
                            class = "formulario__input"
                            id = "monto"
                            name="monto"
                            placeholder="Saldo Inicial" 
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
                //boton nueva tarea
                // if(e.target.classList.contains('submit-nueva-tarea')){
                //     const nombreTarea = document.querySelector('#tarea').value.trim();
                //     if(nombreTarea === ''){
                //         //mostrar alerta de error
                //         mostrarAlerta('El nombre de la tarea es obligatoriio','error',document.querySelector('.formulario legend'));            
                //         return;
                //     }  
             
                //     if(editar){                        
                //         tarea.nombre = nombreTarea;
                //         actualizarTarea(tarea);
                //     }else{
                //         agregarTarea(nombreTarea);
                //     }
                // }
    
            })

            document.querySelector('.dashboard').appendChild(modal); 
        }
        
    }
    
})();
