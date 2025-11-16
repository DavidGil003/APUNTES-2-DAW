/* ------------------------------------------------------------------
   script.js
   Hundir la Flota — versión con comentarios detallados (ES)
   ------------------------------------------------------------------

   Este archivo contiene la lógica del juego: creación de tableros,
   colocación aleatoria de barcos, manejo de clicks del jugador,
   una IA básica para la CPU, renderizado (dibujar el estado en el DOM)
   y comprobación de victoria. Los comentarios explican cada bloque
   y cada función paso a paso.
   ------------------------------------------------------------------ */

/* ------------------------------
   CONFIGURACIÓN Y CONSTANTES
   ------------------------------ */

// Tamaño del tablero (10x10)
const SIZE = 10;

// Arreglo con los tamaños de los barcos — forma clásica:
// portaaviones (5), acorazado (4), crucero (3), submarino (3), patrullero (2)
const SHIPS = [5, 4, 3, 3, 2];

/* ------------------------------
   ESTADO DEL JUEGO (variables)
   ------------------------------
   Aquí guardamos todos los datos que describen el estado actual:
   - las cuadriculas (grids) para jugador y enemigo
   - los arrays con los objetos barco (cada barco tiene coords, hits, sunk)
   - flags para controlar si la partida ha comenzado, etc.
*/
let playerGrid = createGrid(); // tablero del jugador (objetos con {ship, hit})
let enemyGrid = createGrid(); // tablero del enemigo (se ocultan los barcos)
let playerShips = []; // lista de barcos del jugador
let enemyShips = []; // lista de barcos del enemigo

let gameStarted = false; // true cuando la partida ha comenzado
let enemyTurn = false; // true cuando es el turno de la CPU

// Para IA de la CPU: lista de casillas aún disponibles y pila de seguimiento
let availableEnemyTargets = []; // lista de [r,c] sin disparar todavía
let enemyHitStack = []; // pila de vecinos a probar tras un impacto

/* ------------------------------
   REFERENCIAS AL DOM
   ------------------------------
   Guardamos referencias a elementos HTML que usaremos constantemente.
*/
const playerBoardEl = document.getElementById('playerBoard');
const enemyBoardEl = document.getElementById('enemyBoard');
const randomPlaceBtn = document.getElementById('randomPlace');
const startBtn = document.getElementById('startBtn');
const resetBtn = document.getElementById('resetBtn');
const logEl = document.getElementById('log');
const playerLeftEl = document.getElementById('playerLeft');
const enemyLeftEl = document.getElementById('enemyLeft');
const shipsLegend = document.getElementById('shipsLegend');

/* ------------------------------
   FUNCIONES AUXILIARES / ESTRUCTURAS
   ------------------------------ */

/**
 * createGrid()
 * Crea y devuelve una matriz SIZE x SIZE donde cada celda es un objeto:
 * { ship: null | referenciaAlBarco, hit: boolean }
 * - ship: referencia al objeto barco si en esa casilla hay un barco
 * - hit: si la casilla ha sido disparada
 */
function createGrid() {
    return Array.from({ length: SIZE }, () =>
        Array.from({ length: SIZE }, () => ({ ship: null, hit: false }))
    );
}

/* ------------------------------
   CONSTRUCCIÓN DEL TABLERO EN EL DOM
   ------------------------------
   buildBoards crea visualmente los divs que representan las celdas
   para ambos tableros (jugador y enemigo) y pone los listeners.
*/
function buildBoards() {
    // Limpiamos cualquier contenido previo
    playerBoardEl.innerHTML = '';
    enemyBoardEl.innerHTML = '';

    // Creamos SIZE*SIZE celdas en cada tablero
    for (let r = 0; r < SIZE; r++) {
        for (let c = 0; c < SIZE; c++) {
            // Celda del jugador (mostrará barcos y disparos)
            const pCell = document.createElement('div');
            pCell.className = 'cell';
            pCell.dataset.r = r;
            pCell.dataset.c = c;
            // No añadimos click en el tablero del jugador (solo es informativo)
            playerBoardEl.appendChild(pCell);

            // Celda del enemigo (el jugador dispara aquí)
            const eCell = document.createElement('div');
            eCell.className = 'cell';
            eCell.dataset.r = r;
            eCell.dataset.c = c;
            // Cada celda enemiga recibe un listener para el disparo del jugador
            // Usamos una función que captura r/c actuales
            eCell.addEventListener('click', () => onPlayerFire(r, c));
            enemyBoardEl.appendChild(eCell);
        }
    }

    // Construimos la leyenda de barcos (solo visual)
    shipsLegend.innerHTML = '';
    SHIPS.forEach(size => {
        const b = document.createElement('div');
        b.className = 'ship-bubble';
        b.textContent = `Barco ${size} casillas`;
        shipsLegend.appendChild(b);
    });
}

/* ------------------------------
   COLOCACIÓN ALEATORIA DE BARCOS
   ------------------------------
   placeRandomShips(grid)
   Intenta colocar todos los barcos definidos en SHIPS en la
   grilla indicada. Devuelve un array con los objetos barco.
   Reglas aplicadas:
   - Los barcos no se solapan.
   - No se permiten casillas adyacentes (diagonales incluidas).
   - Se prueba orientación horizontal/vertical aleatoriamente.
*/
function placeRandomShips(grid) {
    const ships = [];

    // Para cada tamaño de barco intentamos colocarlo
    for (const size of SHIPS) {
        let placed = false;
        let tries = 0;

        // Intentamos hasta que se coloque (o hasta 1000 intentos como fallback)
        while (!placed && tries < 1000) {
            tries++;
            const dir = Math.random() < 0.5 ? 'h' : 'v';
            const r = Math.floor(Math.random() * SIZE);
            const c = Math.floor(Math.random() * SIZE);
            const coords = [];

            // Calculamos las coordenadas que ocuparía el barco
            for (let i = 0; i < size; i++) {
                const rr = dir === 'h' ? r : r + i;
                const cc = dir === 'h' ? c + i : c;

                // Si sale del tablero, abortamos este intento
                if (rr < 0 || rr >= SIZE || cc < 0 || cc >= SIZE) {
                    coords.length = 0;
                    break;
                }

                // Si ya hay barco en alguna casilla, abortamos
                if (grid[rr][cc].ship !== null) {
                    coords.length = 0;
                    break;
                }

                coords.push([rr, cc]);
            }

            // Si tenemos las coordenadas completas, comprobamos adyacencias
            if (coords.length === size) {
                let ok = true;

                // Para cada casilla del barco comprobamos el área 3x3 alrededor
                for (const [rr, cc] of coords) {
                    for (let ar = rr - 1; ar <= rr + 1; ar++) {
                        for (let ac = cc - 1; ac <= cc + 1; ac++) {
                            if (ar >= 0 && ar < SIZE && ac >= 0 && ac < SIZE) {
                                if (grid[ar][ac].ship !== null) {
                                    ok = false; // hay barco vecino, no permitido
                                }
                            }
                        }
                    }
                }

                if (!ok) continue; // probar otra posición

                // Si todo bien, creamos el objeto barco y lo asignamos a las celdas
                const ship = { size, coords: coords.slice(), hits: 0, sunk: false };
                for (const [rr, cc] of coords) grid[rr][cc].ship = ship;

                ships.push(ship);
                placed = true;
            }
        }

        // Si no se pudo colocar el barco (extremadamente improbable), lo notificamos
        if (!placed) console.warn('No se pudo colocar un barco de tamaño', size);
    }

    return ships;
}

/* ------------------------------
   RENDERIZADO: actualizar el DOM
   ------------------------------
   render() lee el estado de las rejillas (grid) y aplica clases y
   contenido en las celdas DOM para reflejar:
   - barcos visibles en el tablero del jugador
   - impactos (hit) y fallos (miss) en ambos tableros
*/
function render() {
    // Renderizar tablero del jugador: mostramos barcos y disparos
    for (let r = 0; r < SIZE; r++) {
        for (let c = 0; c < SIZE; c++) {
            const cellEl = playerBoardEl.children[r * SIZE + c];
            const state = playerGrid[r][c];

            // Reiniciamos la clase base
            cellEl.className = 'cell';

            // Si hay barco en esa casilla, añadimos clase 'ship' (visible para el jugador)
            if (state.ship) cellEl.classList.add('ship');

            // Si la casilla fue alcanzada (hit), mostramos 'X' y la clase correspondiente
            if (state.hit) {
                cellEl.classList.add(state.ship ? 'hit' : 'miss');
                cellEl.textContent = state.ship ? 'X' : '';
            } else {
                cellEl.textContent = '';
            }
        }
    }

    // Renderizar tablero enemigo: ¡no mostramos barcos que no hayan sido descubiertos!
    for (let r = 0; r < SIZE; r++) {
        for (let c = 0; c < SIZE; c++) {
            const cellEl = enemyBoardEl.children[r * SIZE + c];
            const state = enemyGrid[r][c];

            // Reiniciamos clase
            cellEl.className = 'cell';

            // Solo si la celda fue alcanzada, mostramos hit/miss y la 'X' si hay barco
            if (state.hit) {
                cellEl.classList.add(state.ship ? 'hit' : 'miss');
                cellEl.textContent = state.ship ? 'X' : '';
            } else {
                cellEl.textContent = '';
            }
        }
    }

    // Actualizamos contadores de barcos restantes
    updateCounts();
}

/* ------------------------------
   ACTUALIZAR CONTADORES DE BARCOS
   ------------------------------
   Muestra cuántos barcos no hundidos quedan en cada bando.
*/
function updateCounts() {
    playerLeftEl.textContent = playerShips.filter(s => !s.sunk).length;
    enemyLeftEl.textContent = enemyShips.filter(s => !s.sunk).length;
}

/* ------------------------------
   GESTIÓN DE EVENTOS (botones)
   ------------------------------ */

// Botón: Colocación aleatoria
randomPlaceBtn.addEventListener('click', () => {
    // Reiniciamos rejillas y colocamos barcos aleatoriamente para ambos bandos
    playerGrid = createGrid();
    enemyGrid = createGrid();

    playerShips = placeRandomShips(playerGrid);
    enemyShips = placeRandomShips(enemyGrid);

    // Reiniciamos estado de juego
    gameStarted = false;
    enemyTurn = false;

    // Inicializamos la lista de objetivos disponibles para la CPU
    availableEnemyTargets = [];
    for (let r = 0; r < SIZE; r++) {
        for (let c = 0; c < SIZE; c++) {
            availableEnemyTargets.push([r, c]);
        }
    }

    // Vaciamos pila de hits (IA)
    enemyHitStack = [];

    log('Barcos colocados aleatoriamente. Pulsa "Comenzar partida".');
    render();
});

// Botón: Comenzar partida
startBtn.addEventListener('click', () => {
    // Comprobar que haya barcos colocados (evitar iniciar sin colocación)
    if (!playerShips.length || !enemyShips.length) {
        log('Coloca los barcos primero (Colocación aleatoria).');
        return;
    }
    gameStarted = true;
    enemyTurn = false;
    log('Partida iniciada. ¡Ataca el tablero enemigo!');
    // Deshabilitamos botones que afectarían la setup durante la partida
    startBtn.disabled = true;
    randomPlaceBtn.disabled = true;
});

// Botón: Reiniciar juego
resetBtn.addEventListener('click', () => {
    // Reinicio completo del estado
    playerGrid = createGrid();
    enemyGrid = createGrid();
    playerShips = [];
    enemyShips = [];
    gameStarted = false;
    startBtn.disabled = false;
    randomPlaceBtn.disabled = false;
    log('Juego reiniciado.');
    buildBoards();
    updateCounts();
});

/* ------------------------------
   UTILIDADES PARA LOGS
   ------------------------------ */
function log(msg) {
    logEl.textContent = msg;
}

/* ------------------------------
   MECÁNICA DE DISPARO DEL JUGADOR
   ------------------------------
   onPlayerFire(r, c)
   Llamada cuando el jugador hace click en la celda (r,c) del tablero enemigo.
   - Si no ha comenzado la partida, ignoramos.
   - Si ya se disparó ahí, avisamos.
   - Marcamos la casilla como 'hit' y actualizamos el barco si existe.
   - Si fallas, es turno de la CPU.
*/
function onPlayerFire(r, c) {
    if (!gameStarted) {
        log('La partida no ha comenzado.');
        return;
    }

    const state = enemyGrid[r][c];

    // Evitar disparar dos veces a la misma casilla
    if (state.hit) {
        log('Ya has disparado ahí.');
        return;
    }

    // Marcamos la casilla como disparada
    state.hit = true;

    // Si había barco en esa casilla, aumentamos su contador de impactos
    if (state.ship) {
        state.ship.hits++;
        log('¡Impacto!');

        // Si ya ha recibido tantos impactos como su tamaño, está hundido
        if (state.ship.hits >= state.ship.size) {
            state.ship.sunk = true;
            log('Hundiste un barco enemigo de ' + state.ship.size + ' casillas.');
        }
    } else {
        // Si fallas, el turno pasa a la CPU
        log('Agua. Turno de la CPU.');
    }

    // Volvemos a dibujar los tableros y comprobamos si hay ganador
    render();
    checkWin();

    // Si la partida no ha terminado y el tiro fue un fallo, la CPU juega
    if (!isGameOver() && !state.ship) {
        enemyTurn = true;
        // Pequeña pausa para simular tiempo de la CPU
        setTimeout(enemyPlay, 700);
    }
}

/* ------------------------------
   IA / TURNO DE LA CPU
   ------------------------------
   enemyPlay()
   Lógica sencilla con dos modos:
   - Modo objetivo: si la CPU ha impactado recientemente, prueba las casillas
     adyacentes guardadas en enemyHitStack, lo que le permite hundir barcos.
   - Modo aleatorio: selecciona una casilla aleatoria de availableEnemyTargets.
*/
function enemyPlay() {
    let target;

    // Si hay vecinos pendientes por probar (tras un impacto), usarlos primero
    if (enemyHitStack.length) {
        target = enemyHitStack.pop();
    } else {
        // Si no, elegir un objetivo aleatorio de los disponibles
        const i = Math.floor(Math.random() * availableEnemyTargets.length);
        target = availableEnemyTargets.splice(i, 1)[0];
    }

    // Si por alguna razón target es undefined (lista vacía), hacemos un fallback
    if (!target) {
        // reconstruir availableEnemyTargets por seguridad
        availableEnemyTargets = [];
        for (let r = 0; r < SIZE; r++) {
            for (let c = 0; c < SIZE; c++) {
                if (!playerGrid[r][c].hit) availableEnemyTargets.push([r, c]);
            }
        }
        const i = Math.floor(Math.random() * availableEnemyTargets.length);
        target = availableEnemyTargets.splice(i, 1)[0];
    }

    const [r, c] = target;
    const cell = playerGrid[r][c];

    // Si la CPU seleccionó una casilla ya disparada por un desajuste, reintentar
    if (cell.hit) {
        // Llamamos de nuevo a enemyPlay de forma asíncrona corta
        setTimeout(enemyPlay, 200);
        return;
    }

    // Marcamos la casilla como disparada
    cell.hit = true;

    if (cell.ship) {
        // Si impactó, incrementamos hits del barco y añadimos vecinos a la pila
        cell.ship.hits++;
        log('CPU impacta en tu tablero.');

        // Añadimos vecinos (arriba, abajo, izquierda, derecha) no disparados
        const neighbors = [
            [r - 1, c],
            [r + 1, c],
            [r, c - 1],
            [r, c + 1]
        ].filter(([rr, cc]) => rr >= 0 && rr < SIZE && cc >= 0 && cc < SIZE && !playerGrid[rr][cc].hit);

        // Apilamos los vecinos para que la CPU los pruebe después (comportamiento objetivo)
        for (const n of neighbors) enemyHitStack.push(n);

        // Si con este impacto el barco queda hundido, lo marcamos y limpiamos stack relacionado
        if (cell.ship.hits >= cell.ship.size) {
            cell.ship.sunk = true;
            log('La CPU hundió uno de tus barcos de ' + cell.ship.size + ' casillas.');
            // Limpieza: eliminamos de la pila targets que pertenezcan al barco hundido
            enemyHitStack = enemyHitStack.filter(([rr, cc]) =>
                !cell.ship.coords.some(([sr, sc]) => sr === rr && sc === cc)
            );
        }
    } else {
        // Fallo: le devuelve el turno al jugador
        log('CPU falla. Te toca a ti.');
    }

    // Fin del turno de la CPU: renderizamos y comprobamos victoria
    enemyTurn = false;
    render();
    checkWin();
}

/* ------------------------------
   COMPROBACIONES DE VICTORIA / UTILIDADES
   ------------------------------ */

// isGameOver devuelve true si cualquiera de los dos jugadores ha perdido todos sus barcos
function isGameOver() {
    return playerShips.every(s => s.sunk) || enemyShips.every(s => s.sunk);
}

// checkWin muestra mensajes y resetea flags si hay ganador
function checkWin() {
    if (playerShips.length && playerShips.every(s => s.sunk)) {
        log('Has perdido. La CPU ha hundido todos tus barcos.');
        gameStarted = false;
        startBtn.disabled = false;
        randomPlaceBtn.disabled = false;
    }

    if (enemyShips.length && enemyShips.every(s => s.sunk)) {
        log('¡Victoria! Has hundido todos los barcos enemigos.');
        gameStarted = false;
        startBtn.disabled = false;
        randomPlaceBtn.disabled = false;
    }
}

/* ------------------------------
   INICIALIZACIÓN AL CARGAR
   ------------------------------
   Construimos el tablero visual y dejamos el juego en estado inicial.
*/
buildBoards();
updateCounts();
log('Bienvenido — pulsa "Colocación aleatoria".');