<?php
?>

<html>

<head>
    <title>Simulacion Examen</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <h1>PAÍSES DE LA UNIÓN EUROPEA</h1>
    
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje">
            <?= htmlspecialchars($mensaje) ?>
        </div>
        <br>
    <?php endif; ?>

    <?php
    include "views/listaPaises.php";
    ?>

    <br>

    <form method="post" action="">
        <fieldset>
            <legend>País de la unión europea</legend>
            <label for="pais">País:</label>
            <input type="text" id="pais" name="pais" value="<?= isset($_POST['pais']) ? htmlspecialchars($_POST['pais']) : '' ?>">
            <br>
            <br>
            <label for="capital">Capital:</label>
            <input type="text" id="capital" name="capital" value="<?= isset($_POST['capital']) ? htmlspecialchars($_POST['capital']) : '' ?>">
            <br>
            <br>
            <button type="submit" name="accion" value="add">Añadir país</button>
            <button type="button" onclick="limpiarCampos()">Limpiar Campos</button>
        </fieldset>
    </form>

    <?php if (!empty($paises)): ?>
        <br>
        <form method="post" action="">
            <button type="submit" name="accion" value="vaciar" onclick="return confirm('¿Está seguro de que desea vaciar todo el listado?')">Vaciar Listado</button>
        </form>
    <?php endif; ?>

    <script>
        function limpiarCampos() {
            document.getElementById('pais').value = '';
            document.getElementById('capital').value = '';
        }
    </script>
</body>

</html>