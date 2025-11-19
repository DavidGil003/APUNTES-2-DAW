<?php
$mensaje = "";
$mostrarFormulario = true;

if (isset($_POST["numero_usuario"]) && isset($_POST["numero_random"])) {
    $n = (int) $_POST["numero_usuario"];
    $numeroRandom = (int) $_POST["numero_random"];
    if ($n > $numeroRandom) {
        $mensaje = "<p>Numero demasiado ALTO</p>";
    } else if ($n < $numeroRandom) {
        $mensaje = "<p>Numero demasiado Bajo</p>";
    } else {
        $mensaje = "<p>HAS ADIVINADO EL NUMERO</p>";
        $mostrarFormulario = false;

    }
} else {
    // Primera vez: generar número aleatorio
    $numeroRandom = rand(1, 100);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adivinar Numero Secreto</title>
</head>
<body>
 <?php echo $mensaje; ?>
    <?php if ($mostrarFormulario): ?>
    <h2>Introduce un número para intentar adivinar el número secreto</h2>
    <form action="CalcularNumSecreto.php" method="POST">
        <input type="hidden" name="numero_random" value="<?php echo $numeroRandom; ?>">
        <label for="numero">Número:</label>
        <input type="number" id="numero" name="numero_usuario">
        <br><br>
        <input type="submit" value="Calcular">
           
    </form>
    <?php endif; ?>
</body>
</html>