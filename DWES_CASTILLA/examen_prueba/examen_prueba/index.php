<?php
require_once 'funciones.php';

$mensaje = "";
$pais_input = "";
$capital_input = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'anadir_pais') {
    $pais_input = trim($_POST['pais']);
    $capital_input = trim($_POST['capital']);

    if (empty($pais_input) || empty($capital_input)) {
        $mensaje = "Error: Los campos País y Capital no pueden estar vacíos.";
    } else {
        $paisExistente = existePais($pais_input);

        if ($paisExistente) {
            if ($paisExistente['capital'] !== $capital_input) {
                if (updateCapital($pais_input, $capital_input)) {
                    $mensaje = "País actualizado: La capital de {$pais_input} ha sido cambiada a {$capital_input}.";
                } else {
                    $mensaje = "Error al actualizar la capital de {$pais_input}.";
                }
            } else {
                $mensaje = "El país {$pais_input} ya existe con la capital {$capital_input}. No se realizaron cambios.";
            }
        } else {
            if (addPais($pais_input, $capital_input)) {
                $mensaje = "País añadido: {$pais_input} con capital {$capital_input}.";
                $pais_input = "";
                $capital_input = "";
            } else {
                $mensaje = "Error al añadir el país {$pais_input}.";
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'vaciar_listado') {
    if (vaciarPaises()) {
        $mensaje = "El listado de países ha sido vaciado correctamente.";
    } else {
        $mensaje = "Error al vaciar el listado de países.";
    }
}

$paises = getPaises();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PAISES DE LA UNIÓN EUROPEA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>PAISES DE LA UNIÓN EUROPEA</h2>

    <?php if ($mensaje): ?>
        <p style="color: red; font-weight: bold; text-align: center;"><?= $mensaje ?></p>
    <?php endif; ?>

    <fieldset>
        <legend>Listado paises</legend>
        <div class="listado-paises">
            <?php if (!empty($paises)): ?>
                <?php foreach ($paises as $p): ?>
                    <span class="pais-item"><?= htmlspecialchars($p['nombre']) ?></span>
                    <span class="capital-item"><?= htmlspecialchars($p['capital']) ?></span><br>
                <?php endforeach; ?>
            <?php else: ?>
                <?php endif; ?>
        </div>
    </fieldset>

    <fieldset>
        <legend>País de la unión europea</legend>
        <form method="POST" action="index.php">
            <input type="hidden" name="accion" value="anadir_pais">
            
            <label for="pais">País:</label>
            <input type="text" id="pais" name="pais" value="<?= htmlspecialchars($pais_input) ?>" autocomplete="off"><br>
            
            <label for="capital">Capital:</label>
            <input type="text" id="capital" name="capital" value="<?= htmlspecialchars($capital_input) ?>" autocomplete="off"><br>
            
            <button type="submit">Añadir país</button>
            <button type="button" onclick="document.getElementById('pais').value=''; document.getElementById('capital').value='';">Limpiar Campos</button>
        </form>
    </fieldset>

    <fieldset>
        <legend>Vaciar Listado</legend>
        <form method="POST" action="index.php">
            <input type="hidden" name="accion" value="vaciar_listado">
            <button type="submit" id="vaciar-btn">Vaciar</button>
        </form>
    </fieldset>

</div>
</body>
</html>