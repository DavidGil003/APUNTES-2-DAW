<?php
require_once 'Alquiler.php';

$herramientasAlquiladas = null;
$factura = null;
$hayProductos = false;

if (isset($_POST['productos'])) {
    $hayProductos = true;
    $herramientas = explode('|', $_POST['productos']);
    $alquiler = new Alquiler($herramientas);

    $factura = $alquiler->calcularTotal();

    $herramientasAlquiladas = $alquiler->getHerramientas();
}
?>

<doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>ToolShare - Resumen del Alquiler</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px;
            background: #f7fff7;
            color: #1b2b18
        }

        .container {
            max-width: 980px;
            margin: 0 auto;
            background: #fff;
            padding: 18px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06)
        }

        h1, h2, h3 {
            color: #1a6f2d
        }

        .summary {
            margin-top: 20px;
            padding: 15px;
            background: #f0fff4;
            border-radius: 6px;
            border: 1px solid #d4e8d8;
        }

        .summary p {
            margin: 8px 0;
            font-size: 1.1em;
        }

        button, a.button {
            display: inline-block;
            text-decoration: none;
            background: #1a6f2d;
            color: #fff;
            padding: 9px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            margin-bottom: 25px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
            font-weight: 600;
        }
        td .sku {
            font-size: 0.85em;
            color: #666;
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>ToolShare — Resumen del Alquiler</h1>

        <?php if ($hayProductos): ?>

            <h3>Artículos Alquilados:</h3>
            
            <table>
                <thead>
                    <tr>
                        <th>Herramienta</th>
                        <th>Cantidad</th>
                        <th>Días</th>
                        <th>Precio/día</th>
                        <th>Tipo</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($herramientasAlquiladas as $herramientaString) {
                        [$sku, $nombre, $precioDia, $cantidad, $dias, $tipo, $extra] = explode(':', $herramientaString);
                        
                        $tipoTexto = ($tipo === 'E') ? 'Eléctrica' : 'Manual';
                        
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($nombre) . "<span class='sku'>" . htmlspecialchars($sku) . "</span></td>";
                        echo "<td>" . htmlspecialchars($cantidad) . "</td>";
                        echo "<td>" . htmlspecialchars($dias) . "</td>";
                        echo "<td>" . number_format((float)$precioDia, 2) . " €</td>";
                        echo "<td>" . $tipoTexto . "</td>";
                        echo "<td>" . htmlspecialchars($extra) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="summary">
                <h2>Factura Detallada</h2>
                <p><strong>Subtotal:</strong> <?php echo number_format($factura['subtotal'], 2); ?> €</p>
                <p><strong>IVA (21%):</strong> <?php echo number_format($factura['iva'], 2); ?> €</p>
                <p><strong>Total a Pagar:</strong> <strong><?php echo number_format($factura['total'], 2); ?> €</strong></p>
            </div>

        <?php else: ?>

            <p>No se han seleccionado productos para alquilar o el carrito está vacío.</p>

        <?php endif; ?>

        <a href="index.html" class="button">← Volver al simulador</a>
    </div>
</body>
</html>