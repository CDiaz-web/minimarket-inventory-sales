<div class="barra-mobile">
    <div class="barra-mobile__texto">
        <p>  
        Tienda: <?php echo $_SESSION['tienda'] ?  $_SESSION['tienda']  : 'Elegir Empresa' ; ?>         
        </p>                
    </div> 

    <div class="barra-mobile__menu">       
        <i id="mobile-menu"  class="fa-solid fa-bars"></i>
    </div>
</div>

<div class="barra">
    
        <p>
            <!-- Hola: <span><?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] . '    ' ; ?></span> -->
            Tienda: <span><?php echo $_SESSION['tienda'] ?  $_SESSION['tienda'] : 'Elegir Tienda' ; ?></span>
            
        </p>

    <form method="POST" action="/logout" class="dashboard__form" id="frSalir2">     

        <button class="table__mantenimiento table__mantenimiento--salir"  type="button" id = "cerrar-sesion2"">
                <i class="fa-solid fa-right-from-bracket"></i>  
        </button> 

    </form>
   
</div>
