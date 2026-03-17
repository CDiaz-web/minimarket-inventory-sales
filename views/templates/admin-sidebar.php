<aside class="sidebar"> 

    <nav class="menu-admin">  

        <button class="sidebar__close" id="btnSidebarClose">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="sidebar__brand">

            <i class="fa-solid fa-business-time sidebar__logo"></i>  
            <!-- <img src="<?= $logo ?>" class="sidebar__logo"> -->
            <span class="sidebar__empresa">
                <?php echo $_SESSION['idempresa']; ?>
            </span>

        </div>



        <!-- MENU -->
        <ul class="menu-admin__list">  

            <!-- SALUDO -->
            <!-- <li class="menu-admin__section">
                <span>Hola:</span>
                <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido']; ?>
            </li>  -->

            <?php foreach($opciones as $opcion) { ?>

                <!-- =========================
                     SEPARADOR (GRUPO)
                ========================== -->
                <?php if (!$opcion->idsuperior): ?>

                    <li class="menu-admin__section">
                        <?php echo $opcion->nombre; ?>
                    </li>

                <!-- =========================
                     LINK NORMAL
                ========================== -->
                <?php elseif ($opcion->boton === "0" && $opcion->subnivel === "0" && $opcion->admin === "1"): ?>

                    <li class="menu-admin__item">
                        <a href="/admin<?php echo $opcion->vista;?>"
                           class="menu-admin__link <?php echo pagina_actual($opcion->vista) ? 'menu-admin__link--actual' : ''; ?>">

                            <i class="<?php echo $opcion->icono;?>"></i>

                            <span class="menu-admin__link-texto">
                                <?php echo $opcion->nombre;?>
                            </span>

                        </a>
                    </li>

                <!-- =========================
                     ITEM CON SUBMENU
                ========================== -->
                <?php elseif ($opcion->boton === "1"): ?>

                    <li class="menu-admin__item">

                        <button class="menu-admin__link menu-admin__toggle" type="button">

                            <i class="<?php echo $opcion->icono;?>"></i>

                            <span class="menu-admin__link-texto">
                                <?php echo $opcion->nombre;?>
                            </span>

                        </button>

                        <ul class="menu-admin__submenu">

                            <?php foreach($opciones as $submenu) { ?>
                                <?php if ($submenu->idsuperior == $opcion->id && $submenu->subnivel === "1"): ?>

                                    <li class="menu-admin__subitem">

                                        <a href="/admin<?php echo $submenu->vista;?>"
                                           class="menu-admin__link <?php echo pagina_actual($submenu->vista) ? 'menu-admin__link--actual' : ''; ?>">

                                            <i class="<?php echo $submenu->icono;?>"></i>

                                            <span class="menu-admin__link-texto">
                                                <?php echo $submenu->nombre;?>
                                            </span>

                                        </a>

                                    </li>

                                <?php endif; ?>
                            <?php } ?>

                        </ul>

                    </li>

                <?php endif; ?>

            <?php } ?>

            <!-- =========================
                 LOGOUT
            ========================== -->
            <li class="menu-admin__item menu-admin__logout">

                <form method="POST" action="/logout" id="frSalir">

                    <button type="submit" class="menu-admin__link"  data-action="cerraSesion">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        <span class="menu-admin__link-texto">
                            Cerrar sesión
                        </span>

                    </button>

                </form>

            </li>

        </ul>

    </nav>

</aside>



       