const botones = document.getElementsByClassName("boton");

const botonesArray = Array.from(botones);

const inputCalculadora = document.querySelector(".pantalla input");

const arraySignos = ["x", "/", "-", "+", "*", "%"];

botonesArray.forEach((boton) => {
   boton.addEventListener("mousedown", agregarSombra);

   boton.addEventListener("mouseup", quitarSombra);

   boton.addEventListener("mouseleave", quitarSombra);

   boton.addEventListener("click", agregarNumero);

   boton.addEventListener("click", agregarSignoOperacion);

   boton.addEventListener("click", borrarInput);

   boton.addEventListener("click", agregarPunto);

   boton.addEventListener("click", calcularResultado);

   boton.addEventListener("click", eliminarUltimoCaracter);

   boton.addEventListener("click", agregarParentesis);
});

document.addEventListener("keydown", manejarTeclado)

function agregarSombra() {
   this.classList.add("sombra");
}

function quitarSombra() {
   this.classList.remove("sombra");
}

function manejarTeclado(e) {
    const tecla = e.key;

    if (tecla === 'Enter') {
        e.preventDefault(); 
    }

    if (tecla >= '0' && tecla <= '9') {
        return appendNumero(tecla);
    }
    
    switch (tecla) {
        case '+':
        case '-':
        case '*':
        case '/':
        case '%':
            agregarSignoOperacion(e); 
            break;
            
        case 'Enter':
            calcularResultado(e);
            break;
            
        case 'Backspace':
            eliminarUltimoCaracter(e);
            break;
            
        case '.':
            agregarPunto(e);
            break;

        case '(':
        case ')':
            agregarParentesis(e); 
            break;

        case 'Delete':
            borrarInput(e);
            break;
            
        default:
            return; 
    }
}

function agregarNumero() {
   const valorBoton = parseInt(this.innerText);

   const esNumero = !isNaN(valorBoton);

   if (esNumero) {
      appendNumero(valorBoton);
   }
}

function appendNumero(valor) {
   if (inputCalculadora.value === "0") {
      inputCalculadora.value = valor;
   } else {
      inputCalculadora.value += valor;
   }
}

function borrarInput(e) {
   if (this.innerText === "C" || e.key === "Delete") {
      inputCalculadora.value = "0";
   }
}

function agregarPunto(e) {
   let sePuedePunto = true;

   sePuedePunto = condicionPunto();

   if (sePuedePunto) {
      if (
         (this.innerText === "." || e.key === ".") &&
         inputCalculadora.value == "0"
      ) {
         inputCalculadora.value = "0.";
      } else if (this.innerText === "." || e.key === ".") {
         inputCalculadora.value += ".";
      }
   }
}

function condicionPunto() {
   let boolean = true;

   const valorActual = inputCalculadora.value;
   const ultimoCaracter = valorActual[valorActual.length - 1];

   if (ultimoCaracter === ".") {
      boolean = false;
   }

   let indiceUltimoOperador = -1;

   arraySignos.forEach((signo) => {
      const posicion = valorActual.lastIndexOf(signo);

      if (posicion > indiceUltimoOperador) {
         indiceUltimoOperador = posicion;
      }
   });

   const numeroActual = valorActual.slice(indiceUltimoOperador + 1);

   if (numeroActual.includes(".")) {
      boolean = false;
   }

   return boolean;
}

function agregarSignoOperacion(e) {
   const signoBoton = this.innerText;
   const signoTeclado = e ? e.key : undefined;

   const signoUsar = arraySignos.includes(signoBoton)
      ? signoBoton
      : signoTeclado;

   if (arraySignos.includes(signoUsar)) {
      const valorActual = inputCalculadora.value;
      const ultimoCaracter = valorActual[valorActual.length - 1];

      if (arraySignos.includes(ultimoCaracter)) {
         inputCalculadora.value = valorActual.slice(0, -1) + signoUsar;
      } else {
         inputCalculadora.value += signoUsar;
      }
   }
}

function calcularResultado(e) {
   if (this.innerText === "=" || e.key === "Enter") {
      try {
         let expresion = inputCalculadora.value;

         expresion = expresion.replace(/x/g, "*");

         const resultado = eval(expresion);

         inputCalculadora.value = resultado;
      } catch (error) {
         inputCalculadora.value = "Error";
      }
   }
}

function eliminarUltimoCaracter(e) {
   if (this.innerText === "«" || e.key === "Backspace") {
      const valorActual = inputCalculadora.value;

      if (valorActual !== "0") {
         const nuevoValor = valorActual.slice(0, -1);

         inputCalculadora.value = nuevoValor === "" ? "0" : nuevoValor;
      }
   }
}

function agregarParentesis(e) {
    const valorActual = inputCalculadora.value;
    const ultimoCaracter = valorActual[valorActual.length - 1];
    
    const openCount = valorActual.split('(').length - 1;
    const closeCount = valorActual.split(')').length - 1;
    
    let parenToAdd = '(';
    
    if (openCount > closeCount) {
        if (!arraySignos.includes(ultimoCaracter) && ultimoCaracter !== '.') {
            parenToAdd = ')';
        }
    }
    
    if (parenToAdd === '(') {
        const isPreviousNumOrParen = ultimoCaracter === ')' || !isNaN(parseFloat(ultimoCaracter));
        
        if (valorActual !== '0' && isPreviousNumOrParen) {
            inputCalculadora.value += 'x';
        }
    }
    
    if (valorActual === "0" && parenToAdd === '(') {
        inputCalculadora.value = parenToAdd;
        return; 
    }
    
    if (e && (e.key === '(' || e.key === ')')) {
        inputCalculadora.value += parenToAdd;
    } else if (this && this.innerText === "( )") {
        inputCalculadora.value += parenToAdd;
    }
}