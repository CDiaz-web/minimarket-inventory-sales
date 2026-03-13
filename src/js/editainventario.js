document.addEventListener("DOMContentLoaded", function() {

  // === BOTÓN EDITAR MOVIMIENTO ===
  document.querySelectorAll(".btnEditarMovimiento").forEach(boton => {
    boton.addEventListener("click", function() {
      const idMovimiento = this.dataset.id;

      const modalEl = document.getElementById("modalEditarMovimiento");
      abrirModalInventario(); 
      
      const contenido = document.getElementById("contenidoEditarMovimiento");
      contenido.innerHTML = `
        <div class="text-center py-4">
          <i class="fas fa-spinner fa-spin fa-2x"></i>
          <p>Cargando detalles...</p>
        </div>`;

      // Cargar detalle dinámico
      fetch(`/admin/gestion/logistica/inventario/editar?action=obtenerDetalle&id=${idMovimiento}`)
        .then(res => res.text())
        .then(html => {
          contenido.innerHTML = html;
        })
        .catch(() => {
          contenido.innerHTML = `<p class='text-danger'>Error al cargar el detalle del movimiento.</p>`;
        });
    });
  });


  // === ESCUCHAR CLIC EN BOTÓN GUARDAR ===
  document.addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-guardar")) {
      
      const fila = e.target.closest("tr");
      const cantidad = fila.querySelector(".cantidad").value;
      const costoInput = fila.querySelector(".costo, [name='costo[]']");
      const costo = costoInput && !costoInput.hasAttribute("readonly") ? costoInput.value : null;

      const idDetalle = e.target.dataset.id;
      console.log("🧾 ID Detalle:", idDetalle);

      // Validación básica
      if (!cantidad || (costo !== null && costo === "")) {
        Swal.fire("Atención", "Debe ingresar cantidad y costo.", "warning");
        return;
      }

      // Confirmación con SweetAlert2
      Swal.fire({
        title: "¿Guardar cambios?",
        text: "Se actualizarán los valores del detalle seleccionado.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, guardar",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
              title: "Guardando...",
              text: "Por favor espere",
              allowOutsideClick: false,
              didOpen: () => Swal.showLoading()
            });

            // Enviar los datos al controlador
            fetch("/admin/gestion/logistica/inventario/editar", {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: new URLSearchParams({
                action: "editarDetalle",
                id_detalle: idDetalle,
                cantidad: cantidad,
                costo: costo ?? 0 // Si el costo viene null, enviar 0
              })
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                Swal.fire({
                  icon: "success",
                  title: "Guardado",
                  text: "El detalle se actualizó correctamente.",
                  timer: 1500,
                  showConfirmButton: false
                });

                // Actualizar el total en la tabla
                const totalInput = fila.querySelector(".total");
                if (totalInput && costo !== null) {
                  const total = (parseFloat(cantidad) * parseFloat(costo)).toFixed(2);
                  totalInput.value = total;
                }
              } else {
                Swal.fire("Error", data.message || "No se pudo actualizar el detalle.", "error");
              }
            })
            .catch(error => {
              console.error(error);
              Swal.fire("Error", "Ocurrió un problema al guardar los datos.", "error");
            });            
        }
      });
    }
  });



  // === BOTÓN IMPRIMIR MOVIMIENTO ===
  document.addEventListener("click", function(e) {
    if (e.target.classList.contains("btn-imprimir") || e.target.closest(".btn-imprimir")) {
      const boton = e.target.closest(".btn-imprimir");
      const idMovimiento = boton.dataset.id;

      Swal.fire({
        title: "¿Desea imprimir este movimiento?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, imprimir",
        cancelButtonText: "Cancelar"
      }).then((result) => {
        if (result.isConfirmed) {
          // Abre el PDF en nueva pestaña
          const url = `/admin/gestion/logistica/inventario/imprimir?id=${idMovimiento}`;
          window.open(url, "_blank");
        }
      });
    }
  });

});

function abrirModalInventario() {
  const modal = document.getElementById('modalEditarMovimiento');
  const dialog = modal.querySelector('.modal-inv-dialog');

  // Mostrar modal con animación
  modal.classList.add('show');
  setTimeout(() => dialog.classList.add('show'), 10);

  // Bloquea scroll del body
  document.body.style.overflow = 'hidden';
}

function cerrarModalInventario() {
  const modal = document.getElementById('modalEditarMovimiento');
  const dialog = modal.querySelector('.modal-inv-dialog');

  dialog.classList.remove('show');
  setTimeout(() => {
    modal.classList.remove('show');
    document.body.style.overflow = '';
  }, 400);
}

// Cerrar modal al hacer clic en el botón "Cancelar"
document.addEventListener('click', function(e) {
  if (e.target.hasAttribute('data-bs-dismiss')) {
    cerrarModalInventario();
  }
});
