
document.addEventListener("DOMContentLoaded", () => {

  // Recalcular totales
  document.querySelectorAll("tbody tr").forEach(fila => {
    const cantidadInput = fila.querySelector(".cantidad");
    const costoInput = fila.querySelector(".costo");
    const totalInput = fila.querySelector(".total");

    const recalcular = () => {
      const cantidad = parseFloat(cantidadInput.value) || 0;
      const costo = parseFloat(costoInput.value) || 0;
      totalInput.value = (cantidad * costo).toFixed(2);
    };

    cantidadInput.addEventListener("input", recalcular);
    if (costoInput) costoInput.addEventListener("input", recalcular);
  });

  // Guardar por fila
  document.querySelectorAll(".btn-guardar").forEach(boton => {
    boton.addEventListener("click", async (e) => {
      const fila = e.target.closest("tr");
      const id = fila.dataset.id;
      const cantidad = fila.querySelector(".cantidad").value;
      const costo = fila.querySelector(".costo") ? fila.querySelector(".costo").value : fila.querySelector("input[name='costo']").value;

      const formData = new FormData();
      formData.append("action", "editarDetalle");
      formData.append("id_detalle", id);
      formData.append("cantidad", cantidad);
      formData.append("costo", costo);

      const response = await fetch("/admin/gestion/logistica/Controllers/InventarioController.php", {
        method: "POST",
        body: formData
      });

      const data = await response.json();

      if (data.success) {
        Swal.fire({
          icon: "success",
          title: "Cambios guardados",
          showConfirmButton: false,
          timer: 1200
        });
      } else {
        Swal.fire("Error", data.message || "No se pudo guardar", "error");
      }
    });
  });
});

