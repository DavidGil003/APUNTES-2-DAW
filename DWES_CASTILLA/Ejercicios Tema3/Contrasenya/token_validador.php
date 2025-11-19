<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validación de Token</title>
</head>
<body>
    <?php
    require_once __DIR__ . '/token_form.html';
    $tokenCorrecto = 1234;
    $accesoConcedido = false;
    $mensaje = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $tokenIngresado = isset($_POST["token"]) ? (int)$_POST["token"] : 0;
        if ($tokenIngresado === $tokenCorrecto) {
            $accesoConcedido = true;
        } else {
            $mensaje = "<p style='color:red;'>Token incorrecto. Inténtalo de nuevo.</p>";
        }
    }
    ?>

    <?php if ($accesoConcedido): ?>
        <h2>Acceso Concedido</h2>
    <?php else: ?>
        <?php if ($mensaje) echo $mensaje; ?>
        <form method="post">
            <label for="token">Introduce el token de acceso:</label>
            <input type="number" name="token" id="token" required autofocus>
            <button type="submit">Validar</button>
        </form>
    <?php endif; ?>
</body>
</html>
