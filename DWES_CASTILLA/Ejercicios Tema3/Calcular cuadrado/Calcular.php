<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>
    <h1>Resultado del Cálculo</h1>
    <?php
        // Comprobamos si la variable 'numero_usuario' ha sido enviada
        if (isset($_POST["numero_usuario"])) {
            // Recuperamos el número del formulario.
            // Lo convertimos a entero para asegurar que es un número.
            $n = (int)$_POST["numero_usuario"];

            // Calculamos el cuadrado
            $cuadrado = $n * $n;

            // Mostramos el resultado
            echo "<p>El cuadrado de $n es: <strong>$cuadrado</strong></p>";
        } else {
            echo "<p>No se ha recibido ningún número. Por favor, vuelve al formulario.</p>";
        }
    ?>
    <a href="Formulario.html">Volver a calcular</a>
</body>
</html>