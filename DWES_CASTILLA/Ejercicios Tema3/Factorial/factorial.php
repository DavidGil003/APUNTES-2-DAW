<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>

<body>
    <h1>Resultado del Factorial</h1>
    <?php

    if (isset($_POST["numero_usuario"])) {

        $n = (int) $_POST["numero_usuario"];

        $factorial = 1;



        for ($i = $n; $i > 1; $i--) {
            $factorial *= $i;
        }
        echo "<p>El factorial de $n es $factorial</p>";

    } else {
        echo "<p>No se ha recibido ningún número. Por favor, vuelve al formulario.</p>";
    }
    ?>
    <a href="index_factorial.html">Volver a calcular</a>
</body>

</html>