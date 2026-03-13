export function iniciarSidebar() {

    const admin = document.querySelector('.admin');
    const btnSidebar = document.getElementById('btnSidebarDesktop');
     if (!admin) return;
    /* ==========================
       SUBMENUS (ACORDEON)
    ========================== */

    const toggles = document.querySelectorAll('.menu-admin__toggle');

    toggles.forEach(toggle => {

        toggle.addEventListener('click', () => {

            const item = toggle.closest('.menu-admin__item');

            // cerrar otros abiertos
            document
                .querySelectorAll('.menu-admin__item.activo')
                .forEach(el => {
                    if (el !== item) el.classList.remove('activo');
                });

            // toggle actual
            item.classList.toggle('activo');
        });

    });
        /* ==========================
       RESTAURAR ESTADO SIDEBAR
    ========================== */

    const estadoGuardado = localStorage.getItem('sidebar-collapsed');

    if (estadoGuardado === 'true') {
        admin.classList.add('sidebar-collapsed');
    }


    /* ==========================
       TOGGLE SIDEBAR
    ========================== */

    btnSidebar?.addEventListener('click', () => {

        admin.classList.toggle('sidebar-collapsed');

        // guardar estado
        localStorage.setItem(
            'sidebar-collapsed',
            admin.classList.contains('sidebar-collapsed')
        );
    });
    /* ==========================
       AUTO OPEN (NIVEL PRO)
    ========================== */

    const linkActivo = document.querySelector('.menu-admin__link--actual');

    if (!linkActivo) return;

    const parentItem = linkActivo.closest('.menu-admin__item');

    if (parentItem) {
        parentItem.classList.add('activo');
    }




    /* ==========================
       MOBILE → ABRIR
    ========================== */

    const btnMobile = document.getElementById('btnSidebarMobile');

    btnMobile?.addEventListener('click', () => {
        admin.classList.add('sidebar-open');
    });


    /* ==========================
       MOBILE → CERRAR
    ========================== */

    const btnClose = document.getElementById('btnSidebarClose');

    btnClose?.addEventListener('click', () => {
        admin.classList.remove('sidebar-open');
    });


    /* ==========================
       CERRAR AL CAMBIAR TAMAÑO
    ========================== */

    window.addEventListener('resize', () => {

        if (window.innerWidth >= 1024) {
            admin.classList.remove('sidebar-open');
        }

    });

}