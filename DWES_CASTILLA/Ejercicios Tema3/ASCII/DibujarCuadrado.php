<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado</title>
</head>
<body>
    <h1>Cuadrado en ASCII</h1>
    <?php
if (isset($_POST["ancho"]) && isset($_POST["alto"])) {
    $ancho = (int)$_POST["ancho"];
    $alto = (int)$_POST["alto"];

      echo '<pre style="font-family: monospace">';
            for($i=0; $i< $alto;$i++){

                    for($j= 0; $j< $ancho;$j++){
                        echo "O";
                    }

                    echo "<br>";

            }

            echo "</pre>";
        } else {


            echo "<p> No se han recibido los datos</p>";
        }
           
    ?>
 
</body>
</html>