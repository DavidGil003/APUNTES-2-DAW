let tableroLogico = Array(9)
   .fill(0)
   .map(() => Array(9).fill(0));
let arrayCasillasDOM;

const botonEmpezar = document.getElementById("empezar");

let numerosBorrar = 0;

let casillaSeleccionada = null;

let casillasVacias = 0;

let movimientos = 0;

let errores = 0;
const MAX_ERRORES = 3;

botonEmpezar.addEventListener("click", cargarTablero);

function cargarTablero() {
   const tablero = document.getElementById("tablero");

   asignarDifultad();

   movimientos = 0;
   errores = 0;

   tablero.innerHTML = "";

   tablero.classList.remove("inicio");

   document.getElementById("controles").classList.remove("ocultar");
   document.getElementById("contador").classList.remove("ocultar");
   document.getElementById("contenedorErrores").classList.remove("ocultar");

   agregarCasillas();

   arrayCasillasDOM = Array.from(document.getElementsByClassName("celda"));

   generarSolucion();

   modificarDOM();

   eliminarNumeros();

   tablero.addEventListener("click", (e) => {
      seleccionar(e);
   })
}

document.getElementById("controles").addEventListener("click", (e) => {
   ponerNumero(e);
})


function asignarDifultad() {
   const dificultad = document.getElementById("dificultad").value;
   switch (dificultad) {
      case "facil":
         numerosBorrar = 31;
         break;
      case "medio":
         numerosBorrar = 51;
         break;
      case "dificil":
         numerosBorrar = 71;
         break;
      default:
         numerosBorrar = 31;
         break;
   }

   casillasVacias = numerosBorrar;
}

function agregarCasillas() {
   const tablero = document.getElementById("tablero");

   for (let i = 0; i < 9; i++) {
      for (let j = 0; j < 9; j++) {
         let casilla = document.createElement("div");
         casilla.classList.add("celda", "fija");

         tablero.appendChild(casilla);
      }
   }

   tablero.classList.remove("inicio");
}

function generarSolucion() {
   let [fila, col] = encontrarVacia(tableroLogico);
   if (fila === -1) return true;

   const arrayNumeros = [1, 2, 3, 4, 5, 6, 7, 8, 9];
   shuffle(arrayNumeros);

   for (let num of arrayNumeros) {
      if (esValido(tableroLogico, fila, col, num)) {
         tableroLogico[fila][col] = num;

         if (generarSolucion()) return true;

         tableroLogico[fila][col] = 0;
      }
   }

   return false;
}

function shuffle(array) {
   for (let i = array.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [array[i], array[j]] = [array[j], array[i]];
   }
}

function encontrarVacia(tablero) {
   for (let f = 0; f < 9; f++) {
      for (let c = 0; c < 9; c++) {
         if (tablero[f][c] === 0) {
            return [f, c];
         }
      }
   }

   return [-1, -1];
}

function esValido(tablero, fila, columna, numero) {
   for (let c = 0; c < 9; c++) {
      if (tablero[fila][c] === numero) {
         return false;
      }
   }

   for (let f = 0; f < 9; f++) {
      if (tablero[f][columna] === numero) {
         return false;
      }
   }

   const inicioFilaCaja = Math.floor(fila / 3) * 3;
   const inicioColCaja = Math.floor(columna / 3) * 3;

   for (let f = inicioFilaCaja; f < inicioFilaCaja + 3; f++) {
      for (let c = inicioColCaja; c < inicioColCaja + 3; c++) {
         if (tablero[f][c] === numero) {
            return false;
         }
      }
   }

   return true;
}

function modificarDOM() {
   for (let i = 0; i < arrayCasillasDOM.length; i++) {
      let fila = Math.floor(i / 9);
      let columna = i % 9;

      let valor = tableroLogico[fila][columna];

      if (valor !== 0) {
         arrayCasillasDOM[i].textContent = valor;
      }
   }
}

function eliminarNumeros() {
   const casillas = document.getElementsByClassName("celda");
   const arrayCasillas = Array.from(casillas);

   for (let i = 0; i < numerosBorrar; i++) {
      let numeroEliminar =
         arrayCasillas[Math.floor(Math.random() * arrayCasillas.length)];

      while (numeroEliminar.classList.contains("vacia")) {
         numeroEliminar =
            arrayCasillas[Math.floor(Math.random() * arrayCasillas.length)];
      }

      numeroEliminar.innerHTML = "";
      numeroEliminar.classList.remove("fija");
      numeroEliminar.classList.add("vacia");
   }
}

function seleccionar(e) {
   if (e.target.classList.contains("vacia")) {
      if (e.target.classList.contains("seleccionada")) {
         e.target.classList.remove("seleccionada");
      } else {
         const casillaAnterior = document.querySelector(".seleccionada");
         if (casillaAnterior) {
            casillaAnterior.classList.remove("seleccionada");
         }
         e.target.classList.add("seleccionada");
         casillaSeleccionada = e.target;
      }
   }
}

function ponerNumero(e) {
   if (e.target.classList.contains("numero") && casillaSeleccionada) {
      const numero = e.target.textContent;
      casillaSeleccionada.textContent = numero;

      const { fila, columna } = obtenerPosicion();

      if (!comprobarFila(fila) || !comprobarColumna(columna) || !comprobarBloque(fila, columna)) {
         casillaSeleccionada.classList.add("error");
         sumarErrores();
         casillasVacias++;
      } else {
         if (casillaSeleccionada.classList.contains("error")) {
            casillaSeleccionada.classList.remove("error");
         }
      }

      sumarMovimientos();

      casillaSeleccionada.classList.remove("seleccionada");
      casillaSeleccionada = null;
      casillasVacias--;

      if (casillasVacias === 0) {
         mostrarFinal(false, movimientos)
      }
   }
}

function obtenerPosicion() {
   const arrayCasillas = Array.from(document.getElementsByClassName("celda"));
   const indice = arrayCasillas.indexOf(casillaSeleccionada);

   const fila = Math.floor(indice / 9);
   const columna = indice % 9;

   return { fila, columna };
}

function comprobarFila(fila) {
   const numeros = [];

   for (let c = 0; c < 9; c++) {
      if (!comprobacion(numeros, fila, c)) {
         return false;
      }
   }

   return true;
}

function comprobarColumna(columna) {
   const numeros = [];

   for (let f = 0; f < 9; f++) {
      if (!comprobacion(numeros, f, columna)) {
         return false;
      }
   }

   return true;
}

function comprobarBloque(fila, columna) {
   const numeros = [];

   const inicioFilaCaja = Math.floor(fila / 3) * 3;
   const inicioColCaja = Math.floor(columna / 3) * 3;

   for (let f = inicioFilaCaja; f < inicioFilaCaja + 3; f++) {
      for (let c = inicioColCaja; c < inicioColCaja + 3; c++) {
         if (!comprobacion(numeros, f, c)) {
            return false;
         }
      }
   }

   return true;
}

function comprobacion(numeros, f, c) {
   const indice = f * 9 + c;
   const casilla = document.getElementsByClassName("celda")[indice];
   const texto = casilla.textContent.trim();

   if (texto !== "") {
      const numero = parseInt(texto);

      if (numeros.includes(numero)) {
         return false;
      }
      numeros.push(numero);
   }

   return true;
}

function sumarErrores() {
   if (errores < MAX_ERRORES) {
      errores++;
      document.getElementById("numeroErrores").textContent = errores;
      
      const listaErrores = document.getElementById("listaErrores");
      const errorItem = document.createElement("div");
      errorItem.className = "error-item";
      errorItem.textContent = "❌ Error " + errores;
      listaErrores.appendChild(errorItem);

      if (errores === MAX_ERRORES) {
         mostrarFinal(true, errores);
      }
   }
}

function sumarMovimientos() {
   movimientos++;
   const contadorElemento = document.getElementById("contador");
   contadorElemento.innerHTML = movimientos + "<br>movimientos";
}

function mostrarFinal(isPerdido, info) {
   const protector = document.getElementById("protector");
   const textoFinal = document.getElementById("mensajeFinal")

   if (isPerdido) {
      textoFinal.innerHTML = `<h2>Has perdido con ${info} errores</h2> <p>Nos has conseguido resolver el sudoku</p>`
   } else {
      textoFinal.innerHTML = `<h2>Has ganado con ${info} movimientos</h2> <p>Has conseguido resolver el sudoku</p>`
   }

   protector.classList.remove("ocultar");
   textoFinal.classList.remove("ocultar");
}