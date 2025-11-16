const columnas = 10;
const filas = 10;

let puntuacion = 0;
let minas = 0;

let casillasLibres = 0;

const botonEmpezar = document.getElementById("empezar");

botonEmpezar.addEventListener("click", comprobarNumMinas);

function comprobarNumMinas() {
   const inputBombas = document.querySelector("#numMinas");

   if (inputBombas.value < 5 || inputBombas.value > 50) {
      agregarMensajeError();
   } else {
      minas = parseInt(inputBombas.value);

      casillasLibres = columnas * filas - minas;

      cargarTablero(minas);
   }
}

function agregarMensajeError() {
   const contenedorError = document.getElementById("error");
   contenedorError.innerHTML = "";

   let mensajeError = document.createElement("p");
   mensajeError.textContent = "El número de minas debe estar entre 5 y 50";
   contenedorError.appendChild(mensajeError);
}

function cargarTablero(minas) {
   const inputBombas = document.querySelector("#numMinas");
   const botonEmpezar = document.getElementById("empezar");

   inputBombas.remove();
   botonEmpezar.remove();

   const contenedorError = document.getElementById("error");
   contenedorError.innerHTML = "";

   puntuacion = 0;
   actualizarPuntuacion();

   agregarCasillas();

   agregarMinas(minas);

   calcularNumeros();

   agregarEventListeners();
}

function agregarCasillas() {
   const tablero = document.getElementById("tablero");

   for (let i = 0; i < columnas; i++) {
      for (let j = 0; j < filas; j++) {
         let casillaTablero = document.createElement("div");
         casillaTablero.classList.add("casilla", "oculto");
         tablero.appendChild(casillaTablero);
      }
   }
}

function agregarMinas(minas) {
   const casillas = document.getElementsByClassName("casilla");
   const arrayCasillas = Array.from(casillas);

   for (let i = 0; i < minas; i++) {
      let mina =
         arrayCasillas[Math.floor(Math.random() * arrayCasillas.length)];

      while (mina.classList.contains("mina")) {
         mina = arrayCasillas[Math.floor(Math.random() * arrayCasillas.length)];
      }

      mina.classList.add("mina");
   }
}

function calcularNumeros() {
   const casillas = document.getElementsByClassName("casilla");
   const arrayCasillas = Array.from(casillas);

   for (let i = 0; i < arrayCasillas.length; i++) {
      const casilla = arrayCasillas[i];

      if (casilla.classList.contains("mina")) {
         continue;
      }

      let fila = Math.floor(i / columnas);
      let columna = i % columnas;
      let contador = 0;

      for (let filaOffset = -1; filaOffset <= 1; filaOffset++) {
         for (let columnaOffset = -1; columnaOffset <= 1; columnaOffset++) {
            if (filaOffset === 0 && columnaOffset === 0) {
               continue;
            }

            const nuevaFila = fila + filaOffset;
            const nuevaColumna = columna + columnaOffset;

            if (
               nuevaFila >= 0 &&
               nuevaFila < filas &&
               nuevaColumna >= 0 &&
               nuevaColumna < columnas
            ) {
               const indiceAdyacente = nuevaFila * columnas + nuevaColumna;

               if (arrayCasillas[indiceAdyacente].classList.contains("mina")) {
                  contador++;
               }
            }
         }
      }

      if (contador > 0) {
         casilla.textContent = contador;

         if (contador <= 1) {
            casilla.classList.add("poco");
         } else if (contador <= 2) {
            casilla.classList.add("medio");
         } else {
            casilla.classList.add("mucho");
         }
      }
   }
}

function agregarEventListeners() {
   const casillas = document.getElementsByClassName("casilla");
   const arrayCasillas = Array.from(casillas);

   arrayCasillas.forEach((casilla) => {
      casilla.addEventListener("click", desvelarContenido);
   });
}

function desvelarContenido() {
   let isPerdido = false;

   if (this.classList.contains("oculto")) {
      casillasLibres--;
   }

   this.classList.remove("oculto");

   if (!this.classList.contains("mina")) {
      const valor = parseInt(this.innerHTML) || 0;
      puntuacion += valor + 1 * minas;

      actualizarPuntuacion();
   } else {
      isPerdido = !isPerdido;
      mostrarFinal(isPerdido);
   }

   if (casillasLibres <= 0) {
      mostrarFinal(isPerdido);
   }
}

function actualizarPuntuacion() {
   const campoPuntuacion = document.getElementById("puntos");
   campoPuntuacion.innerHTML = puntuacion + "<br>puntos";
}

function mostrarFinal(isPerdido) {
   const protector = document.getElementById("protector");
   protector.classList.remove("ocultar");

   const mensajeFinal = document.getElementById("mensajeFinal");

   if (isPerdido) {
      mensajeFinal.textContent = `Has perdido con ${puntuacion} puntos`;
   } else {
      mensajeFinal.textContent = `Has ganado con ${puntuacion} puntos`;
   }

   mensajeFinal.classList.remove("ocultar");

   const casillas = document.getElementsByClassName("casilla");
   const arrayCasillas = Array.from(casillas);

   arrayCasillas.forEach((casilla) => {
      casilla.removeEventListener("click", desvelarContenido);
   });

   arrayCasillas.forEach((casilla) => {
      if (casilla.classList.contains("mina")) {
         casilla.classList.remove("oculto");
      }
   });

   agregarBoton("Reiniciar");

   let boton = document.getElementById("reiniciar");

   boton.addEventListener("click", cargarNuevoTablero);
}

function agregarBoton(mensaje) {
   let espaciado = document.createElement("br");
   mensajeFinal.appendChild(espaciado);

   let botonReiniciar = document.createElement("button");
   botonReiniciar.textContent = mensaje;

   mensaje = mensaje.toLowerCase();

   botonReiniciar.id = mensaje;
   mensajeFinal.appendChild(botonReiniciar);
}

function cargarNuevoTablero() {
   const protector = document.getElementById("protector");
   protector.classList.add("ocultar");

   const mensajeFinal = document.getElementById("mensajeFinal");
   mensajeFinal.classList.add("ocultar");
   mensajeFinal.innerHTML = "";

   location.reload();
}
