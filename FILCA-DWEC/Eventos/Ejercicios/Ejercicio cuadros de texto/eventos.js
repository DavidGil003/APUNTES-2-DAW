let contenedorFormulario = document.getElementsByClassName("form-container")[0];

if (contenedorFormulario) {
   const camposImput = contenedorFormulario.querySelectorAll(
      "input, select, textarea"
   );

   const botonEditar = contenedorFormulario.querySelector("#editar");
   const botonBorrar = contenedorFormulario.querySelector("#eliminar");
   const botonAgregar = contenedorFormulario.querySelector("#agregar");

   camposImput.forEach((input) => {
      input.addEventListener("input", comprobarCamposCompletos);
   });

   if (botonEditar) {
      botonEditar.addEventListener("click", habilitarCampos);
   }

   if (botonBorrar) {
      botonBorrar.addEventListener("click", borrarLinea);
   }

   if (botonAgregar) {
      botonAgregar.addEventListener("click", agregarLinea);
   }

   function habilitarCampos() {
      const estaDesactivado = camposImput[0].disabled;
      const debeDesactivarse = !estaDesactivado;

      camposImput.forEach((imput) => {
         imput.disabled = debeDesactivarse;
      });

      if (botonEditar) {
         botonEditar.textContent = debeDesactivarse ? "Editar" : "Guardar";
      }
   }

   function comprobarCamposCompletos() {
      let camponesLlenos = true;

      camposImput.forEach((input) => {
         if (input.value.trim() === "") {
            camponesLlenos = false;
         }
      });

      if (botonBorrar) {
         botonBorrar.disabled = !camponesLlenos;
      }
   }

   function borrarLinea() {
    const botonPresionado = this;

    const formularioActual = botonPresionado.closest('.form-container')

      const nombre = document.getElementById("nombre").value;
      const apellidos = document.getElementById("apellidos").value;
      const dni = document.getElementById("dni").value;

      const confirmar = confirm(
         `Estás seguro de que quieres eliminar al usuario ${nombre} ${apellidos} con DNI: ${dni}`
      );

      if (confirmar) {
         formularioActual.remove();
      }
   }

   function agregarLinea() {
      const nuevoFormulario = contenedorFormulario.cloneNode(true);

      const nuevosCampos = nuevoFormulario.querySelectorAll(
         "input, select, textarea"
      );

      nuevosCampos.forEach((input) => {
         input.value = "";
         input.disabled = true;
      });

      const nuevoBotonEditar = nuevoFormulario.querySelector("#editar");
      if (nuevoBotonEditar) {
         nuevoBotonEditar.textContent = "Editar";
      }

      const nuevoBotonBorrar = nuevoFormulario.querySelector("#eliminar");
      if (nuevoBotonBorrar) {
         nuevoBotonBorrar.disabled = true;
      }

      contenedorFormulario.after(nuevoFormulario);

      contenedorFormulario = nuevoFormulario;
   }
}
