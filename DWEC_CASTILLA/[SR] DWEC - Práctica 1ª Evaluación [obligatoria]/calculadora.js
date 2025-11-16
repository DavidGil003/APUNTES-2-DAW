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

  element.addEventListener('click', function () {
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
  });
});

function calcularValor () {
  let expr = String(pantallaInput.value || '').trim();

  expr = expr.replace(/x/g, '*');
  const resultado = eval(expr);

  return resultado;
}
