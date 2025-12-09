// editagenerarov.js (reemplaza tu archivo actual)


(function () {
  "use strict";

  // Esperar a que cargue el DOM
  document.addEventListener("DOMContentLoaded", () => {
    let tc_encontrado = false;
    let tc = 0;
    const btnGenerar = document.querySelector('#btngenerar');
    if (!btnGenerar) return; // Si no existe el botón, no hacemos nada
    
    // Variables globales del módulo
    let tipospago = [];
    let monedas = [];
    let clientes = [];
    //let articulosSeleccionados = []; // Rellenar cuando el usuario agregue artículos

    // Fecha actual en formato YYYY-MM-DD
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    const fechaActual = `${yyyy}-${mm}-${dd}`;

    // --- Helpers para obtener datos remotos ---
    async function obtenerTiposPago() {
      try {
        const res = await fetch('/api/tipopago');
        tipospago = await res.json();
      } catch (err) {
        console.error('Error obteniendo tipos de pago', err);
        tipospago = [];
      }
    }

    async function obtenerMonedas() {
      try {
        const res = await fetch('/api/monedas');
        monedas = await res.json();
      } catch (err) {
        console.error('Error obteniendo monedas', err);
        monedas = [];
      }
    }

    async function obtenerClientes() {
      try {
        const res = await fetch('/api/clientes');
        const all = await res.json();
        // return all.filter(c => String(c.tipo_persona) === String(tipo_persona));
        return all;
      } catch (err) {
        console.error('Error obteniendo clientes', err);
        return [];
      }
    }

    // Valida si hay artículos (por ahora está deshabilitado; puedes activarlo)
    function verificarArticulosSeleccionados() {
        if (articulosSeleccionados.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "No hay artículos seleccionados",
                text: "Por favor, seleccione al menos un artículo antes de continuar."
            });
            return false;
        }
        return true;      
    }

    // --- Función que crea y muestra el modal ---
    function mostraFormulario(totalVenta, clientesList = []) {
      // Crear el contenedor del modal
     
      const modal = document.createElement('div');
      modal.className = 'modal';
      
      modal.innerHTML = `
        <form class="formulario nueva-tarea" role="dialog" aria-modal="true">
          <legend>Registrar Orden de Venta</legend>
          <fieldset class="formulario__fieldset">
            <div class="formulario__campo">
              <label for="idtipopago" class="formulario__label">Forma de Pago</label>
              <select class="formulario__select" id="idtipopago" name="idtipopago">
                <option value="">-Seleccionar-</option>
              </select>
            </div>
            <div class="formulario__campo">
              <label for="fecha" class="formulario__label">Fecha</label>
              <input type="date" class="formulario__input" id="fecha" name="fecha" />
            </div>
            <div class="formulario__campo">
              <label for="idcliente" class="formulario__label">Cliente</label>
              <select class="formulario__select" id="idcliente" name="idcliente">
                <option value="">-Seleccionar-</option>
              </select>
            </div>

            <div class="formulario__campo">
              <label for="idmoneda" class="formulario__label">Moneda</label>
              <select class="formulario__select" id="idmoneda" name="idmoneda">
                <option value="">-Seleccionar-</option>
              </select>
            </div>

            <div class="formulario__campo">
              <label for="total" class="formulario__label">Importe Total</label>
              <input type="number" class="formulario__input" id="total" name="total" disabled />
            </div>
            <div class="formulario__campo">
              <label for="importe" class="formulario__label">Importe a Pagar</label>
              <input type="number" class="formulario__input" id="importe" name="importe" />
            </div>
            <div class="formulario__campo">
              <label for="vuelto" class="formulario__label">Vuelto</label>
              <input type="number" class="formulario__input" id="vuelto" name="vuelto" disabled />
            </div>

            <div class="formulario__campo">
                <label for="observacion" class="formulario__label">Observación</label>
                <textarea id="observacion" name="observacion"></textarea>
            </div>

          </fieldset>
          <div class="opciones">
            <input type="submit" class="submit-nueva-tarea" value="Registrar"/>
            <button type="button" class="cerrar-modal">Cancelar</button>
          </div>
        </form>
      `;

      // Insertar modal en body (más fiable que en .dashboard)
      document.body.appendChild(modal);

      // Añadir animación
      setTimeout(() => {
        const formulario = modal.querySelector('.formulario');
        if (formulario) formulario.classList.add('animar');
      }, 10);

      // Rellenar selects y campos
      const selectPago = modal.querySelector('#idtipopago');
      const selectCliente = modal.querySelector('#idcliente');
      const inputFecha = modal.querySelector('#fecha');
      const inputTotal = modal.querySelector('#total');
      const inputImporte = modal.querySelector('#importe');
      const inputVuelto = modal.querySelector('#vuelto');
      const selectMoneda = modal.querySelector('#idmoneda');
      // Rellenar formas de pago
      tipospago.forEach(tp => {
        const opt = document.createElement('option');
        opt.value = tp.id;
        opt.textContent = tp.nombre;            
        selectPago.appendChild(opt);
      });

      if (forma_pago) {
        selectPago.value = forma_pago;
      }

      // Rellenar clientes recibidos por parámetro
      clientesList.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.nombre_cliente ;
        selectCliente.appendChild(opt);
      });

      // Rellena Moneda
      monedas.forEach(tp => {
        const opt = document.createElement('option');
        opt.value = tp.id;
        opt.textContent = tp.nombre;
        selectMoneda.appendChild(opt);
      });

      if (moneda_base) {
        selectMoneda.value = moneda_base;
      }
      if (valida_tc == 0) {
        selectMoneda.disabled = true;
      }

      // validacion del tc
      //moneda
      selectMoneda.addEventListener("change", async function () {
          const idMoneda = this.value;
          const fecha = document.querySelector("#fecha").value;

          if (idMoneda === "2") { // dólares
              const data = await validarTipoCambio();
              
              if (!tc_encontrado) {                  
                  this.value = "1"; // regresar a soles
              } else {
                
                  const totalConvertido = totalVenta / tc;       
                  document.getElementById('total').value = totalConvertido.toFixed(2);
                  document.getElementById('importe').value = totalConvertido.toFixed(2);
              }
          }else{
                  document.getElementById('total').value = totalVenta.toFixed(2);
                  document.getElementById('importe').value = totalVenta.toFixed(2);
          }

      });     
      
      //fecha
      document.querySelector("#fecha").addEventListener("change", validarTipoCambio);
      //

      

      if (inputFecha) inputFecha.value = fechaActual;
      if (inputTotal) inputTotal.value = (totalVenta || 0).toFixed(2);
      if (inputImporte) inputImporte.value = (totalVenta || 0).toFixed(2);
      if (inputImporte) {
        inputImporte.addEventListener('input', () => {
          const imp = parseFloat(inputImporte.value) || 0;
          const vuelto = imp - (parseFloat(inputTotal.value) || 0);
          inputVuelto.value = vuelto.toFixed(2);
        });
      }

      // Eventos del modal (cerrar y submit)
      modal.addEventListener('click', (e) => {
        // Cerrar modal
        if (e.target.classList.contains('cerrar-modal')) {
          const formulario = modal.querySelector('.formulario');
          if (formulario) formulario.classList.add('cerrar');
          setTimeout(() => modal.remove(), 300);
        }

        // Submit (botón Registrar)
        if (e.target.classList.contains('submit-nueva-tarea')) {
          e.preventDefault();
          // Validaciones básicas
          const tipoPago = selectPago.value.trim();
          const idCliente = selectCliente.value.trim();
          const impPago = inputImporte.value;
          const impTotal = inputTotal.value;

          if (valida_tc == 1) {
            validarTipoCambio();
            if (tc_encontrado === false){return;}
          }


          if (tipoPago === '') { mostrarAlerta('La Forma de Pago es obligatorio', 'alerta__error', modal.querySelector('legend')); return; }
          if (idCliente === '') { mostrarAlerta('El Cliente es obligatorio', 'alerta__error', modal.querySelector('legend')); return; }
          if (impPago === '' || parseFloat(impPago) < parseFloat(impTotal)) { mostrarAlerta('Importe inválido o menor que el total', 'alerta__error', modal.querySelector('legend')); return; }
         
          // Aquí puedes armar el payload y llamar al SP via fetch (o enviar al controller)
          // ejemplo:
          const payload = {
            fecha: inputFecha.value,
            idcliente: idCliente,
            idtipopago: tipoPago,
            total: parseFloat(impTotal).toFixed(2)
            // + detalle de artículos, etc.
          };
          // TODO: llamada fetch para registrar la OV
    

          // cerrar modal tras operación exitosa
          const formulario = modal.querySelector('.formulario');
          if (formulario) formulario.classList.add('cerrar');
          setTimeout(() => modal.remove(), 400);
        }
      });
    }

   


    // Muestra alertas pequeñas (reutilizable)
    function mostrarAlerta(mensaje, tipo, referencia, accionTexto = null, accionCallback = null) {
      
      const alertaPrevia = document.querySelector('.alerta');
      if (alertaPrevia) alertaPrevia.remove();
      const alerta = document.createElement('div');
      alerta.className = `alerta ${tipo}`;
      alerta.textContent = mensaje;

      if (accionTexto && accionCallback) {
          const boton = document.createElement('button');
          boton.textContent = accionTexto;
          boton.className = 'btn-alerta';
          boton.onclick = accionCallback;
          alerta.appendChild(document.createTextNode(' '));
          alerta.appendChild(boton);
      }

      referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);
      setTimeout(() => alerta.remove(), 5000);
    }


    
   async function validarTipoCambio() {
        const fecha = document.querySelector("#fecha").value;
        const idMoneda = document.querySelector("#idmoneda").value;
        const titulo = document.querySelector("legend");
        //const monedaBase = selectMoneda.dataset.monedaBase; // si guardas eso ahí
        if (!fecha) return; // No validar si aún no eligieron fecha
        if (idMoneda === moneda_base) { 
          tc_encontrado = true; 
          return { success: true, tc: null }; 
        }; // No validar si usa moneda base
        
        const res = await fetch("/admin/gestion/ventas/orden/validarTipoCambio", {
            method: "POST",
            credentials: "include",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ fecha, idMoneda })
        });

        const data = await res.json();

        if (!data.success) {
            mostrarAlerta("No hay tipo de cambio para la fecha seleccionada", "alerta__error", titulo);
            tc_encontrado = false;
            return data; // <--- aquí devolvemos algo útil
        }else{
           tc_encontrado = true;
           tc = parseFloat(data.tc); // Asegura conversión a númer         
           return data;
        }
    }

    // Listener del botón Generar O.V.
    btnGenerar.addEventListener('click', async function (ev) {
      ev.preventDefault();
      if (!verificarArticulosSeleccionados()) return;

      // obtener datos necesarios en paralelo
      await Promise.all([obtenerTiposPago(), obtenerMonedas()]);
      const clientes1 = await obtenerClientes();

      // obtener total de la UI
      const totalVentaSpan = document.querySelector("#totalVenta");
      const totalVenta = totalVentaSpan ? (parseFloat(totalVentaSpan.textContent.trim()) || 0) : 0;

      // finalmente, mostrar el modal
      
      mostraFormulario(totalVenta, clientes1);
    });

  }); // DOMContentLoaded
})(); // IIFE

