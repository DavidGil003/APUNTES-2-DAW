const botonEmpezar = document.getElementById("btn-empezar");
const menuConfig = document.getElementById("configuracion");
const divError = document.getElementById("error");
const dificultad = document.getElementById("dificultad"); // Movida aquí para ser global
const dificultades = ["facil", "medio", "dificil"];

let esValido = false;
let temporizadorInterval = null;
let segundos = 0;

botonEmpezar.addEventListener("click", function () {
  validarDificultad(dificultad);

  if (esValido) {
    const tablero = crearTablero();
    empezarJuego(tablero);
  }
});

function empezarJuego(tablero) {
  const contenedorJuego = document.getElementById("juego-container");
  const casillas = Array.from(tablero.querySelectorAll(".casilla"));

  contenedorJuego.classList.remove("ocultar");

  // const bloques = crearBloques(casillas); // Ya no es necesario para la validación en vivo

  // Añadimos el listener a las casillas de usuario en vaciarCasillas()
  
  // Iniciamos el temporizador
  iniciarTemporizador();
}

function validarDificultad(dificultadJuego) {
  if (dificultades.includes(dificultadJuego.value)) {
    esValido = true;
    divError.textContent = "";
    menuConfig.classList.add("ocultar");
  } else {
    divError.textContent =
      "Debes de seleccionar una dificultad antes de empezar";
    esValido = false;
  }
}

function crearTablero() {
  const solucionBase = [
    5, 3, 4, 6, 7, 8, 9, 1, 2, 6, 7, 2, 1, 9, 5, 3, 4, 8, 1, 9, 8, 3, 4, 2, 5,
    6, 7, 8, 5, 9, 7, 6, 1, 4, 2, 3, 4, 2, 6, 8, 5, 3, 7, 9, 1, 7, 1, 3, 9, 2,
    4, 8, 5, 6, 9, 6, 1, 5, 3, 7, 2, 8, 4, 2, 8, 7, 4, 1, 9, 6, 3, 5, 3, 4, 5,
    2, 8, 6, 1, 7, 9,
  ];
  const divTablero = document.getElementById("tablero-sudoku");

  for (let i = 0; i < 81; i++) {
    const inputCasilla = document.createElement("input");
    inputCasilla.classList.add("casilla");
    inputCasilla.value = solucionBase[i];
    inputCasilla.dataset.index = i;

    const fila = Math.floor(i / 9);
    const col = i % 9;

    inputCasilla.dataset.fila = fila;
    inputCasilla.dataset.col = col;
    inputCasilla.dataset.bloque = Math.floor(fila / 3) * 3 + Math.floor(col / 3);

    if (fila === 2 || fila === 5) {
      inputCasilla.classList.add("borde-inferior");
    }
    if (col === 2 || col === 5) {
      inputCasilla.classList.add("borde-derecho");
    }

    divTablero.appendChild(inputCasilla);
  }
  const casillas = Array.from(divTablero.querySelectorAll(".casilla"));

  rellenarSudoku(casillas);
  vaciarCasillas(casillas); // Ahora esta función también añade los listeners

  return divTablero;
}

function crearBloques(casillas) {
  const bloques = Array.from({ length: 9 }, () => []);

  for (let i = 0; i < 81; i++) {
    const casillaActual = casillas[i];

    const fila = Math.floor(i / 9);
    const col = i % 9;

    const indiceBloque = Math.floor(fila / 3) * 3 + Math.floor(col / 3);

    bloques[indiceBloque].push(casillaActual);
  }

  console.log(bloques);
  return bloques; // Faltaba este return
}

function rellenarSudoku(casillas) {
  const numRellenar = obtenerNumRellenar();
  const m = Math.min(numRellenar | 0, 81);

  const numerosRelleno = new Set();

  while (numerosRelleno.size < m) {
    numerosRelleno.add(Math.floor(Math.random() * 81));
  }

  numerosRelleno.forEach((i) => {
    casillas[i].classList.add("casilla-pista");
    casillas[i].readOnly = true; // Hacemos que las pistas no se puedan editar
  });
}

function obtenerNumRellenar() {
  switch (dificultad.value) {
    case "facil":
      return 50;
    case "medio":
      return 30;
    case "dificil":
      return 10;
  }
}

function vaciarCasillas(casillas) {
  for (let i = 0; i < 81; i++) {
    const casillaActual = casillas[i];

    if (!casillaActual.classList.contains("casilla-pista")) {
      casillas[i].classList.add("casilla-usuario");
      casillas[i].value = "";
      
      // AÑADIMOS EL LISTENER SOLO A LAS CASILLAS DEL USUARIO
      casillaActual.addEventListener("input", comprobarCelda);
    }
  }
}

function comprobarCelda(evento) {
  const celda = evento.target;
  let valor = celda.value;
  
  // --- VALIDACIÓN 1-9 Y LONGITUD ---
  valor = valor.replace(/[^1-9]/g, ''); // Solo permite números del 1 al 9
  if (valor.length > 1) {
    valor = valor.slice(0, 1); // Solo permite un dígito
  }
  celda.value = valor; // Actualiza el valor en el input
  // --- FIN VALIDACIÓN ---


  // 1. Limpiamos errores antiguos
  celda.classList.remove("error-validacion");

  // Si el usuario borra el número, no hay nada que validar
  if (valor === "") {
    return;
  }

  // 2. Obtenemos las coordenadas de la celda
  const fila = celda.dataset.fila;
  const col = celda.dataset.col;
  const bloque = celda.dataset.bloque;
  
  let error = false;

  // 3. Comprobar Fila
  const celdasFila = document.querySelectorAll(`.casilla[data-fila="${fila}"]`);
  for (const celdaFila of celdasFila) {
    if (celdaFila !== celda && celdaFila.value === valor) {
      error = true;
      break;
    }
  }

  // 4. Comprobar Columna
  const celdasCol = document.querySelectorAll(`.casilla[data-col="${col}"]`);
  for (const celdaCol of celdasCol) {
    if (celdaCol !== celda && celdaCol.value === valor) {
      error = true;
      break;
    }
  }

  // 5. Comprobar Bloque
  const celdasBloque = document.querySelectorAll(`.casilla[data-bloque="${bloque}"]`);
  for (const celdaBloque of celdasBloque) {
    if (celdaBloque !== celda && celdaBloque.value === valor) {
      error = true;
      break;
    }
  }
  
  if (error) {
    celda.classList.add("error-validacion");
  }
  
  // 6. Comprobar si hemos ganado
  comprobarVictoria();
}

// --- NUEVAS FUNCIONES DE TEMPORIZADOR ---

function iniciarTemporizador() {
  segundos = 0; // Resetea el contador
  const spanTiempo = document.getElementById("temporizador");
  
  // Limpiamos cualquier temporizador anterior
  if (temporizadorInterval) {
    clearInterval(temporizadorInterval);
  }
  
  // Actualiza el tiempo cada segundo
  temporizadorInterval = setInterval(() => {
    segundos++;
    
    const min = Math.floor(segundos / 60);
    const seg = segundos % 60;
    
    // Formato 00:00
    spanTiempo.textContent = `${min.toString().padStart(2, '0')}:${seg.toString().padStart(2, '0')}`;
    
  }, 1000);
}

function detenerTemporizador() {
  clearInterval(temporizadorInterval);
}

// --- NUEVA FUNCIÓN DE VICTORIA ---

function comprobarVictoria() {
  const casillas = document.querySelectorAll(".casilla");
  
  for (const casilla of casillas) {
    // Si alguna casilla está vacía o tiene un error, no hemos ganado
    if (casilla.value === "" || casilla.classList.contains("error-validacion")) {
      return; // Salimos de la función, no hay victoria
    }
  }
  
  // Si el bucle termina, ¡hemos ganado!
  detenerTemporizador();
  
  const tiempoFinal = document.getElementById("temporizador").textContent;
  const protector = document.getElementById("protector");
  const mensajeFinal = document.getElementById("mensajeFinal");
  
  // Creamos el mensaje
  mensajeFinal.innerHTML = `
    <h2>¡Has ganado!</h2>
    <p>Tu tiempo: ${tiempoFinal}</p>
    <button id="btn-reiniciar">Volver a Jugar</button>
  `;
  
  // Añadimos el evento al nuevo botón
  document.getElementById("btn-reiniciar").addEventListener("click", () => {
    location.reload(); // Recarga la página
  });
  
  // Mostramos la pantalla final
  protector.classList.remove("ocultar");
  mensajeFinal.classList.remove("ocultar");
}