<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>

<body>
    <h1>Resultado del Cálculo</h1>
    <?php

    if (isset($_POST["numero_usuario"])) {

        $n = (int) $_POST["numero_usuario"];


        $resultadoTotal = 0;
        $contador = 1;



        while ($n >= $contador) {

            $resultadoTotal += $contador;


            $contador++;
        }
        echo "<p>El resultado total es de las sumas hasta el numero $n es: $resultadoTotal</p>";

    } else {
        echo "<p>No se ha recibido ningún número. Por favor, vuelve al formulario.</p>";
    }
    ?>
    <a href="Formulario.html">Volver a calcular</a>
</body>

</html>