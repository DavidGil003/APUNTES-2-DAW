let divError = document.getElementById("error");
let btnEmpezar = document.getElementById("empezar");
let numBombas = document.getElementById("numMinas");
let juegoTerminado = false;
let revelarRegistrado = false;

function validarTablero(valor) {
  const n = parseInt(valor);
  const esValido = n >= 5 && n <= 50;

  if (!esValido) {
    divError.textContent = "Tiene que ser un valor entre 5 y 50";
  } else {
    divError.textContent = "";
  }

  return esValido;
}

function crearTablero() {
  const divPadre = document.getElementById("tablero");
  if (!divPadre) return;
  divPadre.innerHTML = "";

  const total = 100;
  for (let i = 0; i < total; i++) {
    const divCasilla = document.createElement("div");
    divCasilla.className = "casilla oculto";
    divCasilla.dataset.index = i;
    divCasilla.dataset.count = "0";
    divPadre.appendChild(divCasilla);
  }
}

function obtenerIndicesVecinos(indice, dimension = 10) {
  const fila = Math.floor(indice / dimension);
  const columna = indice % dimension;
  const vecinos = [];
  for (let i = -1; i <= 1; i++) {
    for (let j = -1; j <= 1; j++) {
      if (i === 0 && j === 0) continue;
      const f2 = fila + i;
      const c2 = columna + j;
      if (f2 >= 0 && f2 < dimension && c2 >= 0 && c2 < dimension) {
        vecinos.push(f2 * dimension + c2);
      }
    }
  }
  return vecinos;
}

function colocarMinas(numMinas, contenedor) {
  const casillas = Array.from(contenedor.querySelectorAll(".casilla"));
  const total = casillas.length;
  const m = Math.min(numMinas | 0, total);
  const minas = new Set();

  while (minas.size < m) {
    minas.add(Math.floor(Math.random() * total));
  }

  // Colocamos las minas
  minas.forEach((i) => casillas[i].classList.add("mina"));

  // Por cada mina, incrementamos el contador de sus vecinos que no son minas
  minas.forEach((idxMina) => {
    const vecinos = obtenerIndicesVecinos(idxMina, 10);
    vecinos.forEach((vIdx) => {
      const celda = casillas[vIdx];
      if (!celda.classList.contains("mina")) {
        const actual = parseInt(celda.dataset.count || "0");
        celda.dataset.count = String(actual + 1);
      }
    });
  });
}

function revelarCasilla(contenedor) {
  if (!contenedor) return;
  contenedor.addEventListener("click", (e) => {
    const casilla = e.target.closest(".casilla");

    if (casilla.classList.contains("mina") || juegoTerminado) {
      juegoTerminado = true;
      perderJuego();
    }

    casilla.classList.remove("oculto");

    const minasAlrededor = parseInt(casilla.dataset.count || "0", 10);
    casilla.textContent = minasAlrededor > 0 ? minasAlrededor : "";

    if (minasAlrededor === 1) casilla.classList.add("poco");
    else if (minasAlrededor === 2) casilla.classList.add("medio");
    else if (minasAlrededor >= 3) casilla.classList.add("mucho");

    calcularPuntuacion();

    if (comprobarVictoria()) {
      ganarJuego();
    }
  });
}

function calcularPuntuacion() {
  let puntuacion = 0;
  const casillas = document.querySelectorAll(".casilla");

  casillas.forEach((c) => {
    if (!c.classList.contains("oculto") && !c.classList.contains("mina")) {
      const v = parseInt(c.dataset.count || "0");
      const numBombasInt = parseInt(numBombas.value);
      puntuacion += (v + 1) * numBombasInt;
    }
  });

  const puntosEl = document.getElementById("puntos");
  if (puntosEl) {
    puntosEl.textContent = `Puntuación: ${puntuacion}`;
  }

  return puntuacion;
}

// Victoria cuando no quedan casillas seguras ocultas
function comprobarVictoria() {
  return document.querySelectorAll(".casilla:not(.mina).oculto").length === 0;
}

function perderJuego() {
  juegoTerminado = true;
  document
    .querySelectorAll(".casilla.mina")
    .forEach((c) => c.classList.remove("oculto"));

  // Mostrar overlay y mensaje final con botón
  const protector = document.getElementById("protector");
  const mensaje = document.getElementById("mensajeFinal");
  if (protector) protector.classList.remove("ocultar");
  if (mensaje) {
    const puntos = calcularPuntuacion(); // muestra puntuación actual
    mensaje.classList.remove("ocultar");
    mensaje.innerHTML = `
      <div class="menu-perder">
         Has perdido con ${puntos} puntos<br/>
        <button id="btnReintentar">Reintentar</button>
      </div>
    `;
    const btn = document.getElementById("btnReintentar");
    if (btn) btn.onclick = reiniciarJuego;
  }
}

function ganarJuego() {
  juegoTerminado = true;
  document
    .querySelectorAll(".casilla.mina")
    .forEach((c) => c.classList.remove("oculto"));

  // Mostrar overlay y mensaje final con botón
  const protector = document.getElementById("protector");
  const mensaje = document.getElementById("mensajeFinal");
  if (protector) protector.classList.remove("ocultar");
  if (mensaje) {
    const puntos = calcularPuntuacion();
    mensaje.classList.remove("ocultar");
    mensaje.innerHTML = `
      <div class="menu-ganar">
         Has ganado con ${puntos} puntos<br/>
        <button id="btnReintentar">Reintentar</button>
      </div>
    `;
    const btn = document.getElementById("btnReintentar");
    if (btn) btn.onclick = reiniciarJuego;
  }
}

function reiniciarJuego() {
  // Ocultar overlay y limpiar mensaje
  const protector = document.getElementById("protector");
  const mensaje = document.getElementById("mensajeFinal");
  if (protector) protector.classList.add("ocultar");
  if (mensaje) {
    mensaje.classList.add("ocultar");
    mensaje.innerHTML = "";
  }
  // Reset estado y arrancar una nueva partida directamente
  juegoTerminado = false;
  const valorActual = parseInt(numBombas.value, 10);
  const m = isNaN(valorActual) ? 5 : Math.max(5, Math.min(50, valorActual));
  iniciarPartida(m);
}

function empezar() {
  btnEmpezar.addEventListener("click", function (e) {
    const input = document.getElementById("numMinas");
    const valor = input ? input.value : "";
    if (!validarTablero(valor)) {
      e.preventDefault();
      e.stopPropagation();
    } else {
      iniciarPartida(valor);
    }
  });
}

function iniciarPartida(valor) {
  const contenedor = document.getElementById("tablero");
  crearTablero();
  colocarMinas(parseInt(valor, 10), contenedor);
  if (!revelarRegistrado) {
    revelarCasilla(contenedor);
    revelarRegistrado = true;
  }
  calcularPuntuacion();
}

empezar();
