<?php
$logData = <<<LOG
[2025-07-28 10:00:00] [INFO] User 'ana' logged in successfully.
[2025-07-28 10:01:15] [DEBUG] Database query executed.
[2025-07-28 10:02:30] [ERROR] Failed to connect to payment gateway.
[2025-07-28 10:03:00] [INFO] User 'luis' updated his profile.
[2025-07-28 10:05:00] [ERROR] Division by zero in financial report generator.
LOG;

$lineas = explode("\n", $logData);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Logs de Error</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 2rem auto;
        }

        th,
        td {
            border: 1px solid #888;
            padding: 0.5rem 1rem;
            text-align: left;
        }

        th {
            background: #eee;
        }

        tr.error {
            background: #ffe5e8ff;
        }
    </style>
</head>

<body>
    <h1>Logs de nivel ERROR</h1>
    <table>
        <tr>
            <th>Fecha y Hora</th>
            <th>Nivel</th>
            <th>Mensaje</th>
        </tr>
        <?php
        foreach ($lineas as $linea) {
            if (strpos($linea, '[ERROR]') !== false) {


                if (preg_match('/^\[(.*?)\] \[(.*?)\] (.*)$/', $linea, $matches)) {
                    $fecha = $matches[1];
                    $nivel = $matches[2];
                    $mensaje = $matches[3];
                    echo "<tr class='error'><td>$fecha</td><td>$nivel</td><td>$mensaje</td></tr>";
                }
            }
        }
        ?>
    </table>
</body>

</html>