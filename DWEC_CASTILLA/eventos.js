
const plantilla = document.getElementById('contenedor').cloneNode(true);


anyadir.addEventListener("click", anyadirContenido);

function anyadirContenido(evento) {


    const elementoNuevo = plantilla.cloneNode(true);

    const contenedor = document.getElementById('contenedor-padre');


    const botonBorrar = elementoNuevo.querySelector('.borrar');
    if (botonBorrar) botonBorrar.addEventListener('click', borrarContenido);

    const botonEditar = elementoNuevo.querySelector('.editar');
    if (botonEditar) botonEditar.addEventListener('click', editarContenido);

    contenedor.appendChild(elementoNuevo);


}

document.querySelectorAll('.borrar').forEach(boton => {

    boton.addEventListener("click", borrarContenido);

});



function borrarContenido(evento) {

    const botonBorrar = evento.target;
    const elementoBorrar = botonBorrar.closest('.contenedor');

    elementoBorrar.remove();

}

document.querySelectorAll('.editar').forEach(boton => {

    boton.addEventListener("click", editarContenido);

});




function editarContenido(evento) {

    const botonEditar = evento.target;

    const elementoEditar = botonEditar.closest('.contenedor');

    var inputs = elementoEditar.querySelectorAll('input');

    inputs.forEach(input => {

        if (input.disabled) {
            input.disabled = false;
        } else {


            input.disabled = true;
        }



    })






}

