

<aside class="dashboard__sidebar"> 
    <nav class="dashboard__menu">     
        <div class="dashboard__logo" href=""> 
            <h2 class="dashboard__logo--titulo">     
                <i class="fa-solid fa-business-time "></i>  
                Arteus
            </h2>
            <div class="cerrar-menu">
                <i class="fa-sharp fa-solid fa-circle-xmark"></i>
            </div> 
        </div>

        <p class="dashboard__separador">
        <span>Hola: </span><?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] . '    ' ; ?>       
        </p> 
        <?php foreach($opciones as $opcion) { ?>

            <?php if (!$opcion->idsuperior): ?>

                <span class="dashboard__separador">
                    <?php echo $opcion->nombre ;?>
                </span>

            <?php elseif ($opcion->boton === "0" and $opcion->subnivel === "0" and $opcion->admin ==="1"): ?>

                <a href= "<?php echo "/admin" . $opcion->vista;?>" class="dashboard__enlace <?php echo pagina_actual( $opcion->vista) ? 'dashboard__enlace--actual' : '' ; ?>">
                    <i class="<?php  echo $opcion->icono;?>"></i>            
                    <span class="dashboard__menu-texto">
                        <?php echo $opcion->nombre ;?>
                    </span>
                </a>
            <?php elseif ($opcion->boton === "0" and $opcion->subnivel === "0" and $opcion->admin ==="0"): ?>
                <a href="<?php echo $opcion->vista;?>" class="dashboard__enlace">
                    <i class="<?php  echo $opcion->icono;?>"></i> 
                    <span class="dashboard__menu-texto">
                    <?php echo $opcion->nombre ;?>
                    </span>
                </a>
            <?php elseif ($opcion->boton === "1"): ?>

                <form class="dropdown-btn dashboard__enlace">                      
                    <i class="<?php  echo $opcion->icono;?>"></i>  
                    <input type="button" value="<?php echo $opcion->nombre ;?>"class="header__desplegable">          
                </form>    

                <!-- Aquí van los submenús -->
                <div class="dropdown-container">
                    <?php foreach($opciones as $submenu) { ?>
                        <?php if ($submenu->idsuperior == $opcion->id && $submenu->subnivel === "1"): ?>
                            <a href="<?php echo "/admin" . $submenu->vista; ?>" class="dashboard__enlace <?php echo pagina_actual($submenu->vista) ? 'dashboard__enlace--actual' : ''; ?>">
                                <i class="<?php echo $submenu->icono; ?>"></i>
                                <span class="dashboard__menu-texto">
                                    <?php echo $submenu->nombre; ?>
                                </span>
                            </a>
                        <?php endif; ?>
                    <?php } ?>
                </div>
               
            <?php endif; ?>

            
        <?php } ?>
              

        <form method="POST" action="/logout" class="dashboard__enlace--salir" id="frSalir">
            <i class="fa-solid fa-right-from-bracket dashboard__enlace--icono"></i>
            <input type="submit" value="Cerrar Sesión" class="header__logout" id = "cerrar-sesion">          
        </form>  
  

</aside>   



       