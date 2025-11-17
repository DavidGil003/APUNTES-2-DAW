// ==================== VARIABLES GLOBALES ====================

// Array con las películas navideñas disponibles
const peliculas = [
    "EL GRINCH",
    "SOLO EN CASA",
    "ELF",
    "POLAR EXPRESS",
    "KLAUS",
    "GREMLINS",
    "ARTHUR CHRISTMAS",
    "FROZEN",
    "MI POBRE ANGELITO"
];

// Variables del estado del juego
let peliculaActual = ""; // Película seleccionada aleatoriamente
let letrasAcertadas = []; // Array para guardar las letras ya acertadas
let letrasUsadas = []; // Array para guardar todas las letras usadas
let errores = 0; // Contador de errores (partes de Papá Noel)
let puntuacion = 0; // Puntuación total del jugador
let juegoActivo = true; // Estado del juego (true = en curso, false = finalizado)

// Array con los IDs de las extremidades de Papá Noel en orden de aparición
const extremidades = [
    "cabeza",
    "cuerpo",
    "brazoIzq",
    "brazoDer",
    "manoIzq",
    "manoDer",
    "piernaIzq",
    "piernaDer",
    "pieIzq",
    "pieDer"
];

// ==================== INICIALIZACIÓN ====================

// Creamos el teclado al cargar la página
crearTeclado();

// Inicializamos el juego
inicializarJuego();

// ==================== FUNCIONES PRINCIPALES ====================

/**
 * Función para inicializar o reiniciar el juego
 */
function inicializarJuego() {
    // Seleccionamos una película aleatoria del array
    peliculaActual = peliculas[Math.floor(Math.random() * peliculas.length)];
    
    // Reiniciamos todas las variables del juego
    letrasAcertadas = [];
    letrasUsadas = [];
    errores = 0;
    puntuacion = 0;
    juegoActivo = true;
    
    // Ocultamos todas las extremidades de Papá Noel
    ocultarPapaNoel();
    
    // Creamos la estructura visual del título de la película
    crearTitulo();
    
    // Actualizamos los elementos visuales
    actualizarPuntuacion();
    document.getElementById("ultimaLetra").innerText = "";
    document.getElementById("contenedorLetrasUsadas").innerHTML = "";
    
    // Habilitamos las teclas del teclado
    habilitarTeclado();
    
    // Eliminamos cualquier cartel o capa de inhabilitación previa
    eliminarCartelFinal();
}

/**
 * Función para ocultar todas las extremidades de Papá Noel al inicio
 */
function ocultarPapaNoel() {
    // Recorremos todas las extremidades y les añadimos la clase "oculto"
    extremidades.forEach(function(extremidadId) {
        document.getElementById(extremidadId).classList.add("oculto");
        document.getElementById(extremidadId).classList.remove("visible");
    });
}

/**
 * Función para crear la estructura visual del título de la película
 */
function crearTitulo() {
    // Obtenemos el contenedor del título
    const contenedorTitulo = document.getElementById("titulo");
    
    // Creamos un div para las letras de la película
    let divPelicula = document.getElementById("pelicula");
    
    // Si ya existe, lo vaciamos. Si no, lo creamos
    if (divPelicula) {
        divPelicula.innerHTML = "";
    } else {
        divPelicula = document.createElement("div");
        divPelicula.id = "pelicula";
        contenedorTitulo.appendChild(divPelicula);
    }
    
    // Recorremos cada carácter de la película
    for (let i = 0; i < peliculaActual.length; i++) {
        const caracter = peliculaActual[i];
        
        // Si es un espacio, creamos un div con clase "blanco"
        if (caracter === " ") {
            const espacio = document.createElement("div");
            espacio.classList.add("blanco");
            espacio.innerText = " ";
            divPelicula.appendChild(espacio);
        } else {
            // Si es una letra, creamos un div con clase "letraPeli"
            const divLetra = document.createElement("div");
            divLetra.classList.add("letraPeli");
            divLetra.classList.add("letraOculta"); // Inicialmente oculta
            divLetra.innerText = caracter;
            divLetra.setAttribute("data-letra", caracter); // Guardamos la letra como atributo
            divPelicula.appendChild(divLetra);
        }
    }
}

/**
 * Función para procesar la letra seleccionada por el jugador
 * @param {string} letra - La letra pulsada
 */
function procesarLetra(letra) {
    // Convertimos la letra a mayúscula
    letra = letra.toUpperCase();
    
    // Verificamos que sea una letra válida (A-Z)
    if (letra.length !== 1 || letra < 'A' || letra > 'Z') {
        return; // Si no es válida, salimos de la función
    }
    
    // Si el juego no está activo, no hacemos nada
    if (!juegoActivo) {
        return;
    }
    
    // Verificamos si la letra ya ha sido usada
    if (letrasUsadas.includes(letra)) {
        marcarLetraRepetida(letra);
        return; // Salimos de la función
    }
    
    // Añadimos la letra al array de letras usadas
    letrasUsadas.push(letra);
    
    // Actualizamos la última letra usada
    document.getElementById("ultimaLetra").innerText = letra;
    
    // Añadimos la letra a la caja de letras usadas
    agregarLetraUsada(letra);
    
    // Verificamos si la letra está en el título de la película
    if (peliculaActual.includes(letra)) {
        // La letra es correcta
        procesarLetraCorrecta(letra);
    } else {
        // La letra es incorrecta
        procesarLetraIncorrecta();
    }
}

/**
 * Función para procesar una letra correcta
 * @param {string} letra - La letra acertada
 */
function procesarLetraCorrecta(letra) {
    // Contamos cuántas veces aparece la letra en el título
    let vecesAparece = 0;
    
    // Obtenemos todos los divs de letras
    const letras = document.querySelectorAll('.letraPeli');
    
    // Recorremos cada letra del título
    letras.forEach(function(divLetra) {
        // Si la letra coincide, la mostramos
        if (divLetra.getAttribute('data-letra') === letra) {
            divLetra.classList.remove("letraOculta");
            divLetra.classList.add("letraVisible");
            vecesAparece++;
            letrasAcertadas.push(letra);
        }
    });
    
    // Calculamos los puntos según la fórmula
    // Base: 1000 puntos por letra
    // Penalización: -100 puntos por cada parte de Papá Noel visible
    const puntosPorLetra = 1000 - (errores * 100);
    const puntosGanados = puntosPorLetra * vecesAparece;
    puntuacion += puntosGanados;
    
    // Actualizamos la puntuación en pantalla
    actualizarPuntuacion();
    
    // Verificamos si se ha completado la película
    verificarVictoria();
}

/**
 * Función para procesar una letra incorrecta
 */
function procesarLetraIncorrecta() {
    // Incrementamos el contador de errores
    errores++;
    
    // Mostramos la siguiente parte de Papá Noel
    if (errores <= extremidades.length) {
        const extremidadId = extremidades[errores - 1];
        const extremidad = document.getElementById(extremidadId);
        extremidad.classList.remove("oculto");
        extremidad.classList.add("visible");
    }
    
    // Verificamos si se ha perdido el juego (10 partes = juego perdido)
    if (errores >= extremidades.length) {
        mostrarDerrota();
    }
}

/**
 * Función para marcar visualmente una letra como repetida
 * @param {string} letra - La letra repetida
 */
function marcarLetraRepetida(letra) {
    // Buscamos el div de la letra en las letras usadas
    const letrasUsadasDivs = document.querySelectorAll('.letraUsada');
    
    letrasUsadasDivs.forEach(function(div) {
        if (div.innerText === letra) {
            // Añadimos la clase "repetida" para marcarla en rojo
            div.classList.add("repetida");
            
            // Después de 300ms, quitamos la marca
            setTimeout(function() {
                div.classList.remove("repetida");
            }, 300);
        }
    });
}

/**
 * Función para añadir una letra usada a la caja de letras usadas
 * @param {string} letra - La letra a añadir
 */
function agregarLetraUsada(letra) {
    const contenedor = document.getElementById("contenedorLetrasUsadas");
    
    // Creamos un div para la letra
    const divLetra = document.createElement("div");
    divLetra.classList.add("letraUsada");
    divLetra.innerText = letra;
    
    // Lo añadimos al contenedor
    contenedor.appendChild(divLetra);
}

/**
 * Función para actualizar la puntuación en pantalla
 */
function actualizarPuntuacion() {
    // Formateamos la puntuación con 8 dígitos, rellenando con ceros a la izquierda
    const puntuacionFormateada = puntuacion.toString().padStart(8, '0');
    document.getElementById("puntos").innerText = puntuacionFormateada;
}

/**
 * Función para verificar si se ha ganado el juego
 */
function verificarVictoria() {
    // Contamos cuántas letras (sin espacios) tiene el título
    const totalLetras = peliculaActual.replace(/ /g, '').length;
    
    // Si hemos acertado todas las letras, ganamos
    if (letrasAcertadas.length >= totalLetras) {
        mostrarVictoria();
    }
}

/**
 * Función para mostrar el cartel de victoria
 */
function mostrarVictoria() {
    juegoActivo = false;
    
    // Creamos la capa de inhabilitación
    const capaInhabilitada = document.createElement("div");
    capaInhabilitada.classList.add("inhabilitado");
    capaInhabilitada.id = "capaInhabilitada";
    document.body.appendChild(capaInhabilitada);
    
    // Creamos el cartel de victoria
    const cartel = document.createElement("div");
    cartel.classList.add("mensaje");
    cartel.classList.add("ganado");
    cartel.id = "cartelFinal";
    
    // Añadimos el contenido del cartel
    cartel.innerHTML = `
        <h2>¡FELICIDADES!</h2>
        <p>Has acertado la película: <strong>${peliculaActual}</strong></p>
        <p>Puntuación final: <strong>${puntuacion}</strong> puntos</p>
        <input type="button" value="Volver a jugar" onclick="reiniciarJuego()">
    `;
    
    document.body.appendChild(cartel);
}

/**
 * Función para mostrar el cartel de derrota
 */
function mostrarDerrota() {
    juegoActivo = false;
    
    // Creamos la capa de inhabilitación
    const capaInhabilitada = document.createElement("div");
    capaInhabilitada.classList.add("inhabilitado");
    capaInhabilitada.id = "capaInhabilitada";
    document.body.appendChild(capaInhabilitada);
    
    // Creamos el cartel de derrota
    const cartel = document.createElement("div");
    cartel.classList.add("mensaje");
    cartel.classList.add("perdido");
    cartel.id = "cartelFinal";
    
    // Añadimos el contenido del cartel
    cartel.innerHTML = `
        <h2>¡LOSER!</h2>
        <p>Has perdido. La película era: <strong>${peliculaActual}</strong></p>
        <input type="button" value="Volver a jugar" onclick="reiniciarJuego()">
    `;
    
    document.body.appendChild(cartel);
}


function eliminarCartelFinal() {
    const cartel = document.getElementById("cartelFinal");
    const capa = document.getElementById("capaInhabilitada");
    
    if (cartel) {
        cartel.remove();
    }
    
    if (capa) {
        capa.remove();
    }
}

/**
 * Función para reiniciar el juego
 */
function reiniciarJuego() {
    inicializarJuego();
}


/**
 * Función para la creación del teclado visual
 */
function crearTeclado() {
    const teclado = document.getElementById("teclado");
    
    // Creamos cada tecla con su carácter correspondiente (A-Z)
    for (let teclaActual = 65; teclaActual <= 90; teclaActual++) {
        const tecla = document.createElement("button");
        tecla.innerText = String.fromCharCode(teclaActual);
        tecla.classList.add("tecla");
        
        // Añadimos evento click a cada tecla
        tecla.addEventListener("click", function() {
            procesarLetra(this.innerText);
        });
        
        teclado.appendChild(tecla);
    }
}

/**
 * Función para habilitar todas las teclas del teclado
 */
function habilitarTeclado() {
    const teclas = document.querySelectorAll('.tecla');
    
    teclas.forEach(function(tecla) {
        tecla.disabled = false;
        tecla.style.opacity = "1";
    });
}

// ==================== EVENTOS DE TECLADO FÍSICO ====================

/**
 * Evento para detectar las teclas presionadas en el teclado físico
 */
document.addEventListener("keydown", function(evento) {
    // Obtenemos la tecla presionada
    const tecla = evento.key.toUpperCase();
    
    // Verificamos que sea una letra (A-Z)
    if (tecla.length === 1 && tecla >= 'A' && tecla <= 'Z') {
        procesarLetra(tecla);
    }
});