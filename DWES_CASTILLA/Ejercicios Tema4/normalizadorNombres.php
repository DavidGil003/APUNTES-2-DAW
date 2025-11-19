<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>

<body>
    <h1> Normalizador Nombre</h1>
    <?php

    if (isset($_POST["nombre_usuario"])) {

        $nombreUsuarioDado = $_POST["nombre_usuario"];



        $nombreCorregido = ucwords(strtolower(trim($nombreUsuarioDado)));


         echo "<p>La cadena original es la siguiente: $nombreUsuarioDado</p>";
        echo "<p>La cadena corregida es la siguiente: $nombreCorregido</p>";
    } else {
        echo "<p>No se ha recibido ningún número. Por favor, vuelve al formulario.</p>";
    }
    ?>
    <a href="   Normalizador.html">Volver a calcular</a>
</body>

</html>