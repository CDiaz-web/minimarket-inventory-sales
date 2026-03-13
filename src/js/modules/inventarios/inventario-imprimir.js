import Swal from "sweetalert2";

export function initImprimeInventario() {

    const tabla = document.querySelector("[data-table]");

    if (!tabla) return;

    document.addEventListener("click", function(e) {

        const btn = e.target.closest("[data-action]");
        if (!btn) return;

        const action = btn.dataset.action;
        const id = btn.dataset.id;



        // ===============================
        // IMPRIMIR
        // ===============================

        if (action === "imprimirMovimiento") {

            Swal.fire({
                title: "¿Desea imprimir este movimiento?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Sí, imprimir",
                cancelButtonText: "Cancelar"
            }).then((result) => {

                if (result.isConfirmed) {

                    window.open(
                        `/admin/gestion/logistica/inventario/imprimir?id=${id}`,
                        "_blank"
                    );

                }

            });

        }

     

    });
   

}
