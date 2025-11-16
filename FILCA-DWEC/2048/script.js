let botonActivo = document.querySelector(".activo");
let tamanoTablero = 0;

const arrayBotonesTamanyo = Array.from(document.querySelectorAll(".btn-tamano"));

arrayBotonesTamanyo.forEach(boton => {
    boton.addEventListener("click", activarBoton);
});

function activarBoton() {
    if (botonActivo) {
        botonActivo.classList.remove("activo");
    }

    this.classList.add("activo");
    botonActivo = this;
}

const botonEmpezar = document.querySelector("#btnEmpezar");
botonEmpezar.addEventListener("click", cargarTablero);

function cargarTablero() {
    tamanoTablero = parseInt(botonActivo.dataset.tamano);

    const contenedorConf = document.getElementById("configuracion")
    contenedorConf.classList.add("ocultar");

    const contenedorJuego = document.getElementById("panelJuego")
    contenedorJuego.classList.remove("ocultar")

    asignarTablero(tamanoTablero);

    ponerNumeros();
}

function asignarTablero(tamano) {
    const tablero = document.querySelector("#tablero");
    tablero.classList.remove("tablero-4x4")

    switch (tamano) {
        case 3:
            tablero.classList.add("tablero-3x3");
            break;
        case 4:
            tablero.classList.add("tablero-4x4");
            break;
        case 5:
            tablero.classList.add("tablero-5x5");
            break;
        default:
            tablero.classList.add("tablero-4x4");
            break;
    }

    agregarCasillas(tamano, tablero)
}

function agregarCasillas(tamano, tablero) {

    for (let i = 0; i < tamano; i++) {
        for (let j = 0; j < tamano; j++) {
            const celda = document.createElement("div");
            celda.classList.add("casilla");
            tablero.appendChild(celda);
        }
    }
}

function ponerNumeros() {
    const arrayCeldas = Array.from(document.querySelectorAll(".casilla"));

    for (let i = 0; i < 2; i++) {
        let num = arrayCeldas[Math.floor(Math.random() * arrayCeldas.length)];

        while (num.classList.contains("ficha-2")) {
            num = arrayCeldas[Math.floor(Math.random() * arrayCeldas.length)];
        }

        num.textContent = 2;
        num.classList.add("ficha-2")
    }
}

const control = Array.from(document.querySelectorAll(".btn-control"));
control.forEach(boton => {
    boton.addEventListener("click", gestionCasillas);
});

function gestionCasillas() {
    const arrayCeldas = Array.from(document.querySelectorAll(".casilla"));

    if (this.dataset.direccion === "derecha") {
        let iteracionMaxima = tamanoTablero;

        for (let fila = 0; fila < tamanoTablero; fila++) {
            let arrayFila = [];
            for (let columna = 0; columna < tamanoTablero; columna++) {
                let indice = obtenerIndice(fila, columna, tamanoTablero);

                arrayFila.push(arrayCeldas[indice]);

                console.log(`Fila: ${fila} - Columna: ${columna} - Tamaño Tablero: ${tamanoTablero} = Indice: ${indice}`);
            }

            desplazarCasillasDerecha(arrayFila);
        }
    }
}

function desplazarCasillasDerecha(arrayFila) {
    for (let i = 0; i < arrayFila.length; i++) {
        if (arrayFila[i].textContent !== "") {
            arrayFila[i].classList.remove("ficha-2")
            arrayFila[i].textContent = ""

            arrayFila[calcularBorde(i)].classList.add("ficha-2")
            arrayFila[calcularBorde(i)].textContent = 2;
        }
    }
}

function calcularBorde(indice) {
    if (indice >= (tamanoTablero - 1)) {
        return (tamanoTablero - 1);
    }

    if (++indice.textContent !== "") {
        return indice;
    }

    return ++indice;
}

function obtenerIndice(fila, columna, tamano) {
    return fila * tamano + columna;
}

