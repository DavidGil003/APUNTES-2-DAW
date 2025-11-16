let botones = document.querySelectorAll('.boton');
let pantallaInput = document.querySelector('#pantalla input');

const operadores = ['+', '-', 'x', '/', '%'];

botones.forEach(element => {
  element.addEventListener('mousedown', function () {
    this.classList.add('sombra-interior');
  })

  element.addEventListener('mouseup', function () {
    this.classList.remove('sombra-interior');
  })

  element.addEventListener('keydown', function () {
    this.classList.remove('sombra-interior');
  })

  element.addEventListener('click', funcionBotones);
});

document.addEventListener('keydown', funcionTeclado);


function funcionBotones(){

    const botonTexto = this.textContent.trim();
    let valorActual = pantallaInput.value;
    const ultimoCaracter = valorActual.slice(-1);

    if (!isNaN(parseInt(botonTexto)) || botonTexto === '0') {
      if (valorActual === '0') {
        pantallaInput.value = botonTexto;
      } else if (valorActual.length < 13) {
        pantallaInput.value = valorActual + botonTexto;
      }
    } else if (operadores.includes(botonTexto)) {
      if (valorActual !== '0' && !operadores.includes(ultimoCaracter)) {
        if (valorActual.length < 13) {
          pantallaInput.value = valorActual + botonTexto;
        }
      }
    }

    if (botonTexto === 'C') {
      pantallaInput.value = '0';
    }

    if (botonTexto === '=') {
      pantallaInput.value = calcularValor();
    }

    if ((botonTexto === '()') && !operadores.includes(ultimoCaracter)) {
      pantallaInput.value = '(' + pantallaInput.value + ')';
    }

    if (botonTexto === '«') {

      pantallaInput.value = pantallaInput.value.slice(0, -1);


    }
    if ((botonTexto === '.') && !operadores.includes(ultimoCaracter)) {
    const numeros = pantallaInput.value.split(/[\+\-x\/\%]/);
    const ultimoNumero = numeros[numeros.length - 1];
  
    if (!ultimoNumero.includes('.')) {
      pantallaInput.value = pantallaInput.value + '.';
  }
    }


}

function calcularValor() {
  let expr = String(pantallaInput.value || '').trim();

  expr = expr.replace(/x/g, '*');
  const resultado = eval(expr);

  return resultado;
}

function funcionTeclado(e) {

  const key = e.key;


  if (key === 'Enter') {
    e.preventDefault();
    pantallaInput.value = calcularValor();
    return;
  }

 
  if (key === 'Escape' || key.toLowerCase() === 'c') {
    e.preventDefault();
    pantallaInput.value = '0';
    return;
  }


  if (key === 'Backspace') {
    e.preventDefault();
    if (pantallaInput.value.length <= 1) {
      pantallaInput.value = '0';
    } else {
      pantallaInput.value = pantallaInput.value.slice(0, -1);
    }
    return;
  }


  if (/^[0-9]$/.test(key)) {
    e.preventDefault();
    if (pantallaInput.value === '0') {
      pantallaInput.value = key;
    } else if (pantallaInput.value.length < 13) {
      pantallaInput.value += key;
    }
    return;
  }


  if (key === '.' || key === ',') {
    e.preventDefault();
    const decimalChar = '.';
    const numeros = pantallaInput.value.split(/[\+\-x\/\%]/);
    const ultimoNumero = numeros[numeros.length - 1];
  
    if (!ultimoNumero.includes(decimalChar) && pantallaInput.value.length < 13) {
      pantallaInput.value += decimalChar;
    }
    return;
  }

  let op = null;
  if (key === '+' || key === '-' || key === '/' || key === '%') op = key;
  if (key === '*' || key.toLowerCase() === 'x') op = 'x';
  if (op) {
    e.preventDefault();
    const ultimo = pantallaInput.value.slice(-1);
    if (!operadores.includes(ultimo) && pantallaInput.value !== '0') {
      if (pantallaInput.value.length < 13) pantallaInput.value += op;
    } else if (pantallaInput.value === '0' && op === '-') {
      pantallaInput.value = '-';
    }
    return;
  }

  if (key === '(' || key === ')') {
    e.preventDefault();
    if (pantallaInput.value === '0') {
      pantallaInput.value = key === '(' ? '(' : '0)';
    } else if (pantallaInput.value.length < 13) {
      pantallaInput.value += key;
    }
    return;
  }

}