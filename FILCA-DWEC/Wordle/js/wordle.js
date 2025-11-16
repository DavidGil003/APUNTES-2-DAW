// 4 letras
const palabras4 = ["GATO", "CASA", "MESA", "MANO", "PIES", "CAMA", "PELO",
    "ROJO", "AZUL", "AGUA", "AMOR"];

// 5 letras
const palabras5 = ["LLAVE", "PERRO", "LIBRO", "MUNDO", "FUEGO", "NOCHE"];

// 6 letras
const palabras6 = ["TIERRA", "MUSICA", "PUERTA", "COMIDA"];

// 7 letras
const palabras7 = ["VENTANA", "FAMILIA", "ESCUELA", "IGLESIA", "MONTAÑA",
    "TRABAJO", "TECLADO", "MONITOR"];

let palabraAleatoria = "";

let longitudPalabra = 0;
let fila = 0;
let posicionPalabra = 0;

let isPalabraAdivinada = false;

const botonEmpezar = document.getElementById("btnEmpezar");

botonEmpezar.addEventListener("click", comprobarLongitud)

function comprobarLongitud() {
    const campoLong = document.querySelector("#longitud");
    const numero = parseInt(campoLong.value);

    if (numero < 4 || numero > 7) {
        agregarMensajeError();
    } else {
        longitudPalabra = numero;
        seleccionarPalabra();
        cargarTablero();
    }
}

function seleccionarPalabra() {
    switch (longitudPalabra) {
        case 4:
            palabraAleatoria = palabras4[Math.floor(Math.random() * palabras4.length)];
            break;
        case 5:
            palabraAleatoria = palabras5[Math.floor(Math.random() * palabras5.length)];
            break;
        case 6:
            palabraAleatoria = palabras6[Math.floor(Math.random() * palabras6.length)];
            break;
        case 7:
            palabraAleatoria = palabras7[Math.floor(Math.random() * palabras7.length)];
            break;
        default:
            palabraAleatoria = palabras5[Math.floor(Math.random() * palabras5.length)];
            break;
    }
}

function agregarMensajeError() {
    const contenedorError = document.getElementById("error");
    contenedorError.innerHTML = "";

    const textoError = document.createElement("p");
    textoError.textContent = "La longitud de la palabra tiene que estar entre 4 y 7";

    contenedorError.appendChild(textoError);
}

function cargarTablero() {
    mostrarTablero();

    const tablero = document.getElementById("tablero");

    agregarCasillas(tablero);

    marcarCasilla();
}

function mostrarTablero() {
    const contenedorConf = document.getElementById("configuracion")
    contenedorConf.classList.add("ocultar");

    ["marcador", "tablero", "teclado"].forEach(id => {
        document.getElementById(id).classList.remove("ocultar");
    });
}

function agregarCasillas(tablero) {
    for (let i = 0; i < 6; i++) {
        const fila = document.createElement("div");
        fila.classList.add("fila");

        tablero.appendChild(fila);
        for (let j = 0; j < longitudPalabra; j++) {
            const casilla = document.createElement("div");
            casilla.classList.add("casilla", "vacia");

            fila.appendChild(casilla);
        }
    }
}

function marcarCasilla() {
    fila = parseInt(document.getElementById("intentoActual").textContent) - 1;

    const casillaAnterior = document.querySelector(".activa");

    const casillasFila = document.querySelectorAll(".fila")[fila].getElementsByClassName("casilla");
    const arrayCasillas = Array.from(casillasFila);

    if (posicionPalabra >= 0 && posicionPalabra < arrayCasillas.length) {
        arrayCasillas[posicionPalabra].classList.add("activa")
    }

    if (casillaAnterior) {
        casillaAnterior.classList.remove("activa");
    }
}

const arrayTeclado = Array.from(document.getElementsByClassName("tecla"));

arrayTeclado.forEach(tecla => {
    tecla.addEventListener("click", tecladoVirtual)
});

document.addEventListener("keydown", tecladoFisico);

function tecladoVirtual() {
    let letra = "";

    if ((this.dataset.letra)) {
        letra = this.dataset.letra;
        ponerLetra(letra);
    }
}

function tecladoFisico(e) {
    const tecla = e.key.toUpperCase();

    if (tecla.length === 1 && /^[A-Z]$/.test(tecla)) {
        ponerLetra(tecla);
    }

    if (tecla === "ENTER") {
        validarPalabra();
    }

    if (tecla === "BACKSPACE") {
        eliminarLetra();
    }
}

function ponerLetra(letra) {
    const casillaSeleccionada = document.querySelector(".activa");

    if (casillaSeleccionada) {
        casillaSeleccionada.textContent = letra;

        if (letra != "") {
            if (posicionPalabra < (longitudPalabra - 1)) {
                posicionPalabra++;
            }

            marcarCasilla();
        }
    }
}

const teclaValidar = document.querySelector('[data-tecla="ENTER"]');
teclaValidar.addEventListener("click", validarPalabra);

function validarPalabra() {
    const arrayPalabra = Array.from(palabraAleatoria);
    const casillasFila = document.querySelectorAll(".fila")[fila].getElementsByClassName("casilla");
    const arrayCasillas = Array.from(casillasFila);

    if (comprobarFilaLlena(arrayCasillas)) {
        for (let i = 0; i < arrayCasillas.length; i++) {
            const letra = arrayCasillas[i].textContent;
            const elementoLetra = arrayCasillas[i];

            if (letra === arrayPalabra[i]) {
                elementoLetra.classList.add("correcta");
            }
        }

        for (let i = 0; i < arrayCasillas.length; i++) {
            const letra = arrayCasillas[i].textContent;
            const elementoLetra = arrayCasillas[i];

            if (!elementoLetra.classList.contains("correcta")) {
                const letraEnPalabra = arrayPalabra.filter(l => l === letra).length;
                const letraCorrectas = arrayCasillas.filter((cas, idx) => 
                    cas.textContent === letra && cas.classList.contains("correcta")
                ).length;

                if (letraEnPalabra > letraCorrectas && arrayPalabra.includes(letra)) {
                    elementoLetra.classList.add("presente");
                } else {
                    elementoLetra.classList.add("ausente");
                }
            }

            marcarLetra(letra, elementoLetra);
        }

        let contadorCorrectas = 0;

        for (let i = 0; i < arrayCasillas.length; i++) {
            if (arrayCasillas[i].classList.contains("correcta")) {
                contadorCorrectas++;
            }
        }

        console.log(contadorCorrectas)

        if (contadorCorrectas === longitudPalabra) {
            isPalabraAdivinada = true;
        }

        console.log(isPalabraAdivinada)

        console.log(palabraAleatoria);

        if (isPalabraAdivinada) {
            mostrarFinal(true);
        }

        actualizarIntento();

        posicionPalabra = 0;

        marcarCasilla();
    }
}

function marcarLetra(letra, elementoLetra) {
    arrayTeclado.forEach(tecla => {
        if (tecla.textContent === letra) {
            tecla.classList.remove("correcta", "presente", "ausente");
            
            if (elementoLetra.classList.contains("correcta")) {
                tecla.classList.add("correcta")
            } else if (elementoLetra.classList.contains("presente")) {
                tecla.classList.add("presente")
            } else if (elementoLetra.classList.contains("ausente")) {
                tecla.classList.add("ausente")
            }
        }
    });
}

function comprobarFilaLlena(arrayCasillas) {
    for (let i = 0; i < arrayCasillas.length; i++) {
        if (arrayCasillas[i].textContent === "") {
            return false;
        }
    }

    return true;
}

function actualizarIntento() {
    const intentoActual = document.querySelector("#intentoActual");

    intentoActual.textContent = calcularIntento();
}

function calcularIntento() {
    fila++;

    if (fila === 6) {
        mostrarFinal(false);
        return fila;
    }

    return fila + 1;
}

const teclaEliminar = document.querySelector('[data-tecla="BORRAR"]');
teclaEliminar.addEventListener("click", eliminarLetra);

function eliminarLetra() {
    if (posicionPalabra >= 0) {
        const casillaActual = document.querySelector(".activa");

        if (casillaActual) {
            casillaActual.textContent = "";
        }

        if (posicionPalabra > 0) {
            posicionPalabra--;
            marcarCasilla();
        }
    }
}

function mostrarFinal(isGanado) {
    const protector = document.getElementById("protector");
    protector.classList.remove("ocultar")
}