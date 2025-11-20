document.addEventListener('DOMContentLoaded', function() {
    const btnCambiarTema = document.getElementById('btnCambiarTema');
    
    if (btnCambiarTema) {
        btnCambiarTema.addEventListener('click', function() {
            // Redirigir al archivo que cambia el tema
            window.location.href = 'cambiar-tema.php';
        });
    }
});
