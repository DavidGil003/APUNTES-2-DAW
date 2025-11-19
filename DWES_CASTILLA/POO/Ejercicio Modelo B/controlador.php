<?php
// Activar reporte de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar clases en el orden correcto
require_once __DIR__ . '/Tool.php';
require_once __DIR__ . '/ElectricTool.php';
require_once __DIR__ . '/ManualTool.php';
require_once __DIR__ . '/Rental.php';

echo "<!doctype html><html lang='es'><head><meta charset='utf-8'>
<title>Resumen alquiler ToolShare</title>
<style>
body{font-family:Arial,Helvetica,sans-serif;margin:20px;background:#f9fff9;color:#1b2b18}
table{border-collapse:collapse;width:100%;margin-top:15px}
th,td{padding:8px;border-bottom:1px solid #ddd;text-align:left}
th{background:#1a6f2d;color:#fff}
h1{color:#1a6f2d}
.summary{margin-top:20px;padding:10px;background:#f0fff4;border-radius:6px;width:400px}
</style></head><body>";

echo "<h1>Resumen del alquiler ToolShare</h1>";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["productos"])) {
    $rental = new Rental();
    $productos = explode("|", $_POST["productos"]);

    foreach ($productos as $linea) {
        $partes = explode(":", $linea);

        
        if (count($partes) < 6) continue;

        list($sku, $nombre, $precioDia, $cantidad, $dias, $tipo) = $partes;
        $extra = $partes[6] ?? ''; 

        
        $precioDia = (float) $precioDia;
        $cantidad  = (int) $cantidad;
        $dias      = (int) $dias;

        
        if ($tipo === "E") {
           
            $potencia = $extra ?: '0W';
            $tool = new ElectricTool($sku, $nombre, $precioDia, "Herramienta eléctrica", $potencia);
        } else {
            
            $descripcion = $extra ?: "Herramienta manual";
            $tool = new ManualTool($sku, $nombre, $precioDia, $descripcion);
        }

      
        if ($tool->validar()) {
            try {
                $rental->addTool($tool, $cantidad, $dias);
            } catch (Exception $e) {
                echo "<p style='color:red'>Error en $sku: {$e->getMessage()}</p>";
            }
        } else {
            echo "<p style='color:red'>Datos inválidos para el producto $sku.</p>";
        }
    }

    $rental->mostrarResumen();

    echo "<div class='summary'>";
    echo "<p><strong>Subtotal:</strong> €" . number_format($rental->calcularSubtotal(), 2) . "</p>";
    echo "<p><strong>IVA (21%):</strong> €" . number_format($rental->calcularIVA(), 2) . "</p>";
    echo "<p><strong>Total:</strong> €" . number_format($rental->calcularTotal(), 2) . "</p>";
    echo "</div>";

} else {
    echo "<p>No se recibieron datos de productos.</p>";
}

echo "<p><a href='form_B.html'>← Volver al formulario</a></p>";
echo "</body></html>";
?>