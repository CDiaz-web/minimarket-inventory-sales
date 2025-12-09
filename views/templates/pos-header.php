<div class="pos-barra">
    <p>Hola: <span><?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido']; ?></span></p>

    <p class="barra__texto">        
        <?php echo $_SESSION['tienda'] ?  $_SESSION['tienda'] : 'Elegir Tienda' ; ?>
    </p>   

    <!-- <?php
        include_once __DIR__ .'/menu-exportar.php';  
    ?> -->

    <!-- <form method="POST" action="/logout" class="dashboard__form">    
        <input type="submit" value="Cerrar Sesión" class="header__logout" id = "cerrar-sesion">          
    </form> -->
    <!-- <form method="POST" action="/logout" class="dashboard__enlace" id="frSalir">
            <i class="fa-solid fa-right-from-bracket dashboard__enlace--icono"></i>
            <input type="submit" value="Cerrar Sesión" class="header__logout" id = "cerrar-sesion">          
    </form> -->
</div>