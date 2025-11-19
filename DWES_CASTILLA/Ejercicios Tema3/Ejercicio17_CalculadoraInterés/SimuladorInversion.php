<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>

<body>
    <h1>Resultado del Cálculo</h1>
    <?php

    if (isset($_POST["capital_inicial"]) && isset($_POST["tasa_interes"]) && isset($_POST["anyos_Inversion"])) {

        $capitalInicial = (int) $_POST["capital_inicial"];
        $tasaInteres = (int) $_POST["tasa_interes"];
        $anyosInversion = (int) $_POST["anyos_Inversion"];

        $anyo;

        $balanceFinal;
        echo '<h2>Calculo Interes Fijo</h2>';
        echo '<table>';
        echo '<tr> <th> Año</th> <th>Interés Ganado</th> <th>Balance Final</th> </tr>';
        for ($anyo = 1; $anyo <= $anyosInversion; $anyo++) {


            $interes = $capitalInicial * ($tasaInteres / 100);
            $balanceFinal = $capitalInicial + ($interes * $anyo);



            echo '<tr>';
            echo "<td> $anyo </td>";
            echo "<td>  $interes </td>";
            echo "<td>  $balanceFinal </td>";
            echo "</tr>";

        }

        echo '</table>';
        echo "<br></br>";


    } else {
        echo "<p>No se ha recibido ningún número. Por favor, vuelve al formulario.</p>";
    }
    ?>
    <a href="FormularioInversion.html">Volver a calcular</a>
</body>

</html>