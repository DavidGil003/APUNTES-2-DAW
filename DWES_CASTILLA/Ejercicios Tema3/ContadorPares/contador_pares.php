<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pares hasta N</title>
</head>
<body>
    <h1>Números pares hasta N</h1>
    <?php
    if (isset($_POST["numero"])) {
        $n = (int)$_POST["numero"];
        if ($n > 10) {
            echo "<p>Pares desde 2 hasta $n:</p>";
            echo "<ul>";
            for ($i = 2; $i <= $n; $i += 2) {
                echo "<li>$i</li>";
            }
            echo "</ul>";
        } else {
            echo "<p style='color:red;'>El número debe ser mayor que 10.</p>";
        }
    } else {
        echo "<p>No se recibió ningún número.</p>";
    }
    ?>
    <a href="contador_form.html">Volver al formulario</a>
</body>
</html>
