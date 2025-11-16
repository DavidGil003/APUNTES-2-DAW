const rojo = document.querySelector("#rojo");
const azul = document.querySelector("#azul");
const verde = document.querySelector("#verde");
const negro = document.querySelector("#negro");

// Obtener los botones
const botones = document.querySelectorAll("button");

// Event listeners para rojo
rojo.addEventListener("mouseenter", function() { cambiarForma("rojo"); });
rojo.addEventListener("mouseleave", function() { restaurarForma("rojo"); });
rojo.addEventListener("mousedown", function() { quitarSombra("rojo"); });
rojo.addEventListener("mouseup", function() { restaurarSombra("rojo"); });
rojo.addEventListener("dblclick", function() { fijarCirculoConSombraInterior("rojo"); });

// Event listeners para azul
azul.addEventListener("mouseenter", function() { cambiarForma("azul"); });
azul.addEventListener("mouseleave", function() { restaurarForma("azul"); });
azul.addEventListener("mousedown", function() { quitarSombra("azul"); });
azul.addEventListener("mouseup", function() { restaurarSombra("azul"); });
azul.addEventListener("dblclick", function() { fijarCirculoConSombraInterior("azul"); });

// Event listeners para verde
verde.addEventListener("mouseenter", function() { cambiarForma("verde"); });
verde.addEventListener("mouseleave", function() { restaurarForma("verde"); });
verde.addEventListener("mousedown", function() { quitarSombra("verde"); });
verde.addEventListener("mouseup", function() { restaurarSombra("verde"); });
verde.addEventListener("dblclick", function() { fijarCirculoConSombraInterior("verde"); });

// Event listeners para negro
negro.addEventListener("mouseenter", function() { cambiarForma("negro"); });
negro.addEventListener("mouseleave", function() { restaurarForma("negro"); });
negro.addEventListener("mousedown", function() { quitarSombra("negro"); });
negro.addEventListener("mouseup", function() { restaurarSombra("negro"); });
negro.addEventListener("dblclick", function() { fijarCirculoConSombraInterior("negro"); });

// Event listeners para botones eliminar
botones[0].addEventListener("click", function() { eliminarElemento("rojo"); });
botones[1].addEventListener("click", function() { eliminarElemento("azul"); });
botones[2].addEventListener("click", function() { eliminarElemento("verde"); });
botones[3].addEventListener("click", function() { eliminarElemento("negro"); });

function cambiarForma(id) {
    const elemento = document.querySelector("#" + id);
    if (!elemento.classList.contains("fijo")) {
        elemento.classList.add("hover-effect");
    }
}

function restaurarForma(id) {
    const elemento = document.querySelector("#" + id);
    if (!elemento.classList.contains("fijo")) {
        elemento.classList.remove("hover-effect");
    }
}

function quitarSombra(id) {
    const elemento = document.querySelector("#" + id);
    if (!elemento.classList.contains("fijo")) {
        elemento.classList.add("sin-sombra");
    }
}

function restaurarSombra(id) {
    const elemento = document.querySelector("#" + id);
    if (!elemento.classList.contains("fijo")) {
        elemento.classList.remove("sin-sombra");
    }
}

function fijarCirculoConSombraInterior(id) {
    const elemento = document.querySelector("#" + id);
    elemento.classList.remove("hover-effect", "sin-sombra"); 
    
    elemento.classList.add("fijo", "sombra-interior"); 
}

function eliminarElemento(id) {
    const elemento = document.querySelector("#" + id);
    const contenedor = elemento.parentElement;
    contenedor.remove();
}