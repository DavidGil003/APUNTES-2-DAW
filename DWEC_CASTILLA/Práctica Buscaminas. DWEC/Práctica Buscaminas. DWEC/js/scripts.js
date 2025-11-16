let botonEmpezar = document.getElementById("empezar");
let divError = document.getElementById("error");
let esValido = false;
let puntuacion = 0;
let numMinasActual = null; 

botonEmpezar.addEventListener("click", () => {
  const numBombas = document.getElementById("numMinas");
  const valor = numBombas.value;
  numMinasActual = valor;
  empezarJuego(valor);
});

function empezarJuego(valorInicial) {
  const contenedor = document.getElementById("tablero");
  puntuacion = 0;
  document.getElementById("puntos").textContent = "0 Puntos";

  validarDatos(valorInicial);

  if (esValido) {
    crearTablero();
    colocarMinas(valorInicial, contenedor);
    asignarValorCasilla(contenedor);
    calcularPuntuacion(valorInicial, contenedor); 
  }
}

function validarDatos(valor) {

  if (valor === "" || valor === null) {
    esValido = false;
    divError.textContent = ""; 
    return;
  }

  if (valor >= 5 && valor <= 50) {
    esValido = true;
    divError.textContent = "";
  } else {
    esValido = false;
    divError.textContent = "Tiene que ser un valor entre 5 y 50";
  }
}

function crearTablero() {
  const divTablero = document.getElementById("tablero");

  if (!divTablero) return;
  divTablero.innerHTML = "";

  for (let i = 0; i < 100; i++) {
    const divCasilla = document.createElement("div");
    divCasilla.className = "casilla oculto";
    divCasilla.dataset.index = i;
    divCasilla.dataset.count = "0";
    divTablero.appendChild(divCasilla);
  }

  const casillasTablero = document.querySelectorAll(".casilla");

  casillasTablero.forEach((element) => {
    element.addEventListener("click", function () {});
  });
}

function colocarMinas(numMinas, contenedor) {
  const casillas = Array.from(contenedor.querySelectorAll(".casilla"));
  const total = casillas.length;

  const m = Math.min(numMinas | 0, total);

  const minas = new Set();

  while (minas.size < m) {
    minas.add(Math.floor(Math.random() * total));
  }

  minas.forEach((i) => casillas[i].classList.add("mina"));

  minas.forEach((idxMina) => {
    const vecinos = obtenerIndices(idxMina, 10);

    vecinos.forEach((idxVecino) => {
      const casillaVecina = casillas[idxVecino];

      if (!casillaVecina.classList.contains("mina")) {
        let valor = parseInt(casillaVecina.dataset.count) || 0;

        valor++;

        casillaVecina.dataset.count = valor;
      }
    });
  });
}

function obtenerIndices(indice, dimension = 10) {
  const fila = Math.floor(indice / dimension);
  const columna = indice % dimension;
  const vecinos = [];

  for (let i = -1; i <= 1; i++) {
    for (let j = -1; j <= 1; j++) {
      const f2 = fila + i;
      const c2 = columna + j;

      if (f2 >= 0 && f2 < dimension && c2 >= 0 && c2 < dimension) {
        vecinos.push(f2 * dimension + c2);
      }
    }
  }
  return vecinos;
}

function asignarValorCasilla(contenedor) {
  contenedor.addEventListener("click", (e) => {
    const casilla = e.target.closest(".casilla");

    if (!casilla || !casilla.classList.contains("oculto")) {
      return;
    }

    casilla.classList.remove("oculto");
    const minasAlrededor = parseInt(casilla.dataset.count || "0", 10);

    casilla.textContent = minasAlrededor > 0 ? minasAlrededor : "";

    if (minasAlrededor === 1) {
      casilla.classList.add("poco");
    } else if (minasAlrededor === 2) {
      casilla.classList.add("medio");
    } else if (minasAlrededor >= 3) {
      casilla.classList.add("mucho");
    }

    if (hasGanado(contenedor)) {
      ganarJuego();
    }
  });
}

function calcularPuntuacion(numMinas, contenedor) {
  const puntos = document.getElementById("puntos");

  contenedor.addEventListener("click", (e) => {
    const casilla = e.target.closest(".casilla");
    if (!casilla) return;

    if (casilla.classList.contains("mina")) {
      perderJuego();
      return;
    }

    if (!casilla.dataset.puntuada) {
      const minasAlrededor = parseInt(casilla.dataset.count || "0", 10);
      puntuacion += (minasAlrededor + 1) * parseInt(numMinas);
      puntos.textContent = puntuacion + " Puntos";

      casilla.dataset.puntuada = "true"; 
    }
  });
}

function perderJuego() {
  const divProtector = document.getElementById("protector");
  const divMensajeFinal = document.getElementById("mensajeFinal");
  divMensajeFinal.innerHTML = `<div class ="menu-perder"> Has perdido con ${puntuacion} puntos <br/> <button id="btnReintentar">Volver a Jugar</button></div>`;
  document
    .getElementById("btnReintentar")
    .addEventListener("click", reiniciarJuego);
  divProtector.classList.remove("ocultar");
  divMensajeFinal.classList.remove("ocultar");
}

function hasGanado(contenedor) {
  const casillas = contenedor.querySelectorAll(".casilla");

  let haGanado = true;

  casillas.forEach((c) => {
    if (!c.classList.contains("mina") && c.classList.contains("oculto")) {
      haGanado = false;
    }
  });

  return haGanado;
}

function ganarJuego() {
  const divProtector = document.getElementById("protector");
  const divMensajeFinal = document.getElementById("mensajeFinal");
  divMensajeFinal.innerHTML = `<div class ="menu-ganar"> Has ganado con ${puntuacion} puntos <br/> <button id="btnReintentar"> Volver a Jugar </button></div>`;
  document
    .getElementById("btnReintentar")
    .addEventListener("click", reiniciarJuego);
  divProtector.classList.remove("ocultar");
  divMensajeFinal.classList.remove("ocultar");
}

function reiniciarJuego() {

  document.getElementById("protector").classList.add("ocultar");
  document.getElementById("mensajeFinal").classList.add("ocultar");

  document.getElementById("tablero").innerHTML = "";

  if (numMinasActual) {
    empezarJuego(numMinasActual);
  }
}


