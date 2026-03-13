


<div class="topbar">

    <!-- IZQUIERDA -->
    <div class="topbar__left">

        <!-- MOBILE: abre sidebar -->
        <button
            id="btnSidebarMobile"
            class="topbar__btn topbar__btn--mobile">
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- DESKTOP: colapsa sidebar -->
        <button
            id="btnSidebarDesktop"
            class="topbar__btn topbar__btn--desktop">
            <i class="fa-solid fa-bars"></i>
        </button>

        <a href="/tiendas" class="topbar__title topbar__tienda">
            <i class="fa-solid fa-store"></i>
            <?php echo $_SESSION['tienda'] ?: 'Elegir Tienda'; ?>
            <i class="fa-solid fa-chevron-down"></i>
        </a>

    </div>


    <!-- DERECHA -->
    <div class="topbar__right">

        <div class="topbar__user">
            <div class="topbar__avatar">
                <?php echo strtoupper(substr($_SESSION['nombre'],0,1)); ?>
            </div>

            <span class="topbar__name">
                <?php echo $_SESSION['nombre']; ?>
            </span>
        </div>

        <form id="frSalir" method="POST" action="/logout">
            <button class="topbar__btn" type="button" data-action="cerraSesion">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </form>

    </div>

</div>