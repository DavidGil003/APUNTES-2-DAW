// Sudoku en JS: genera una solución válida, crea un puzzle con huecos según dificultad
// y monta una UI mínima con contador de errores y panel de dígitos.
// Requiere en el HTML:
// - div#digits (panel de números 1..9)
// - div#board (tablero 9x9)
// - span#errors (contador)
// - botones #btn-easy, #btn-medium, #btn-hard, #btn-new

let numSelected = null;   // referencia al botón de dígito seleccionado (panel 1..9)
let tileSelected = null;  // (no se usa aquí) podría guardar la celda clicada
let errors = 0;           // contador de errores del usuario

var board = [];           // puzzle visible por el usuario (con huecos "-")
var solution = [];        // solución completa (siempre 9 strings de 9 caracteres)

// Conteo de usos de cada dígito (1..9). Si llega a 9, se oculta el botón del panel.
const digitCounts = Array(10).fill(0);

// Dificultades: número de pistas (cuantas más, más fácil)
const difficultyClues = { easy: 42, medium: 35, hard: 20 };
let currentDifficulty = "medium";

// Al cargar la página: configura botones de dificultad y crea una partida inicial
window.onload = function() {
    const btnEasy = document.getElementById("btn-easy");
    const btnMedium = document.getElementById("btn-medium");
    const btnHard = document.getElementById("btn-hard");
    const btnNew = document.getElementById("btn-new");

    // Activa dificultad y reinicia partida al pulsar
    if (btnEasy) btnEasy.addEventListener("click", () => { setActiveDifficulty("btn-easy"); initGame("easy"); });
    if (btnMedium) btnMedium.addEventListener("click", () => { setActiveDifficulty("btn-medium"); initGame("medium"); });
    if (btnHard) btnHard.addEventListener("click", () => { setActiveDifficulty("btn-hard"); initGame("hard"); });
    if (btnNew) btnNew.addEventListener("click", () => initGame(currentDifficulty));

    setActiveDifficulty("btn-medium");
    initGame(currentDifficulty);
};

/**
 * Marca visualmente el botón de dificultad activo y actualiza currentDifficulty.
 */
function setActiveDifficulty(activeId){
    const map = { "btn-easy":"easy", "btn-medium":"medium", "btn-hard":"hard" };
    ["btn-easy","btn-medium","btn-hard"].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        if (id === activeId) el.classList.add("active");
        else el.classList.remove("active");
    });
    currentDifficulty = map[activeId] || "medium";
}

/**
 * Inicia una nueva partida:
 * - Genera una solución válida
 * - Crea un puzzle con huecos según dificultad
 * - Monta el tablero y panel de números
 */
function initGame(level){
    if (level) currentDifficulty = level;
    const clues = difficultyClues[currentDifficulty] || difficultyClues.medium;

    solution = generateRandomSolution();     // solución 9x9 consistente
    board = generatePuzzle(solution, clues); // mismo formato pero con "-"
    setGame();                               // renderiza y enlaza eventos
}

/**
 * Genera una solución de Sudoku válida de forma determinista + aleatorizaciones:
 * - Parte de una base 9x9 correcta
 * - Permuta dígitos 1..9
 * - Reordena filas dentro de bandas y columnas dentro de pilas
 * - Reordena bandas y pilas completas
 */
function generateRandomSolution(){
    // Base correcta (cada fila respeta reglas)
    let base = [
        "123456789",
        "456789123",
        "789123456",
        "214365897",
        "365897214",
        "897214365",
        "531642978",
        "642978531",
        "978531642"
    ];
    // Fisher–Yates
    function shuffle(arr){
        let a = arr.slice();
        for (let i = a.length - 1; i > 0; i--){
            let j = Math.floor(Math.random() * (i+1));
            [a[i], a[j]] = [a[j], a[i]];
        }
        return a;
    }
    // Permuta dígitos globalmente (1..9 -> permutación aleatoria)
    const digits = shuffle([1,2,3,4,5,6,7,8,9]);
    const map = {};
    for (let i = 1; i <= 9; i++) map[i] = digits[i-1];
    base = base.map(row => row.split("").map(ch => map[parseInt(ch)]).join(""));

    // Reordenación de filas dentro de cada banda (grupos de 3 filas)
    for (let band = 0; band < 3; band++){
        const order = shuffle([0,1,2]).map(o => band*3 + o);
        const newRows = order.map(r => base[r]);
        for (let i = 0; i < 3; i++) base[band*3 + i] = newRows[i];
    }
    // Reordenación de columnas dentro de cada pila (grupos de 3 columnas)
    for (let stack = 0; stack < 3; stack++){
        const order = shuffle([0,1,2]).map(o => stack*3 + o);
        for (let r = 0; r < 9; r++){
            const rowArr = base[r].split("");
            const seg = order.map(c => rowArr[c]);
            for (let i = 0; i < 3; i++) rowArr[stack*3 + i] = seg[i];
            base[r] = rowArr.join("");
        }
    }
    // Reordenación de bandas completas (bloques de 3 filas)
    const bandOrder = shuffle([0,1,2]);
    base = bandOrder.map(b => base.slice(b*3, b*3+3)).flat();

    // Reordenación de pilas completas (bloques de 3 columnas)
    const stackOrder = shuffle([0,1,2]);
    base = base.map(row => stackOrder.map(s => row.slice(s*3, s*3+3)).join(""));
    return base; // array de 9 strings de 9 dígitos (sin "-")
}

/**
 * Crea un puzzle a partir de la solución quitando celdas al azar hasta dejar "clues" pistas.
 * Devuelve mismo formato que solution pero con "-" en huecos.
 */
function generatePuzzle(solved, clues){
    const total = 81;
    let toRemove = total - clues;
    const grid = solved.map(r => r.split(""));
    // Elimina celdas aleatorias sin repetir posición
    while (toRemove > 0){
        const pos = Math.floor(Math.random() * 81);
        const r = Math.floor(pos / 9);
        const c = pos % 9;
        if (grid[r][c] !== "-"){
            grid[r][c] = "-";
            toRemove--;
        }
    }
    return grid.map(r => r.join(""));
}

/**
 * Renderiza panel de dígitos y tablero 9x9.
 * - Las celdas con número inicial se bloquean (tile-start)
 * - Bordes gruesos cada 3 filas/columnas
 * - Eventos de click para elegir dígitos y colocar en tablero
 */
function setGame(){
    // Reset UI
    document.getElementById("digits").innerHTML = "";
    document.getElementById("board").innerHTML = "";
    errors = 0;
    document.getElementById("errors").innerText = "0";
    for (let i = 1; i <= 9; i++) digitCounts[i] = 0;

    // Panel de números 1..9
    for (let i = 1; i <= 9; i++){
        let number = document.createElement("div");
        number.id = i; // id = "1".."9"
        number.innerText = i;
        number.addEventListener("click", selectNumber);
        number.classList.add("number");
        document.getElementById("digits").appendChild(number);
    }

    // Tablero 9x9
    for (let r = 0; r < 9; r++){
        for (let c = 0; c < 9; c++){
            let tile = document.createElement("div");
            tile.id = r + "-" + c; // ej: "3-7"
            const ch = board[r][c];
            if (ch !== "-"){
                // Pista inicial (no editable)
                tile.innerText = ch;
                tile.classList.add("tile-start");
                digitCounts[parseInt(ch)]++; // contar dígitos iniciales para ocultar botones al llegar a 9
            }
            // Líneas de subcuadrícula 3x3
            if (r === 2 || r === 5) tile.classList.add("horizontal-line");
            if (c === 2 || c === 5) tile.classList.add("vertical-line");
            // Click para intentar colocar número
            tile.addEventListener("click", selectTile);
            tile.classList.add("tile");
            document.getElementById("board").append(tile);
        }
    }
    // Oculta del panel los dígitos que ya están 9 veces
    updateDigitVisibility();
}

/**
 * Al pulsar un número del panel, lo marca como seleccionado.
 * Si ya está oculto (9 usos), no hace nada.
 */
function selectNumber(){
    if(numSelected){
        numSelected.classList.remove("number-selected");
    }
    if (this.style.visibility === "hidden"){
        numSelected = null;
        return;
    }
    numSelected = this;
    numSelected.classList.add("number-selected");
}

/**
 * Al pulsar una celda del tablero:
 * - Si coincide con la solución, coloca el dígito y actualiza conteos
 * - Si no, incrementa errores
 * - No permite sobreescribir pistas ni celdas ya rellenadas
 */
function selectTile(){
    if(!numSelected) return;          // sin número seleccionado
    if(this.innerText !== "") return; // ya hay un valor (pista o acertado)
    let [r,c] = this.id.split("-").map(Number);
    if (solution[r][c] === numSelected.id){
        // Acierto: coloca y actualiza conteo del dígito
        this.innerText = numSelected.id;
        const d = parseInt(numSelected.id);
        digitCounts[d]++;
        updateDigitVisibility();
    } else {
        // Error: suma 1
        errors++;
        document.getElementById("errors").innerText = errors;
    }
}

/**
 * Oculta los botones de dígitos cuyo conteo llegó a 9 (ya no quedan en el tablero).
 * Si el dígito ocultado estaba seleccionado, des-selecciona.
 */
function updateDigitVisibility(){
    for (let d = 1; d <= 9; d++){
        const el = document.getElementById(d.toString());
        if (!el) continue;
        if (digitCounts[d] >= 9){
            el.style.visibility = "hidden";
            if (numSelected && numSelected.id === d.toString()){
                numSelected.classList.remove("number-selected");
                numSelected = null;
            }
        } else {
            el.style.visibility = "visible";
        }
    }
}