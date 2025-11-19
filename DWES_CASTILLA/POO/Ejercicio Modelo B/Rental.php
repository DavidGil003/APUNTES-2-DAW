<?php

require_once 'Tool.php';

class Rental {
    private array $tools = [];
    private array $cantidades = [];
    private array $dias = [];

    public function __construct() {
       
    }

    public function addTool(Tool $tool, int $cantidad, int $dias): void {
        if ($cantidad <= 0 || $dias <= 0) {
            throw new Exception("Cantidad y días deben ser mayores que 0.");
        }

        $this->tools[] = $tool;
        $this->cantidades[] = $cantidad;
        $this->dias[] = $dias;
    }

    public function calcularSubtotal(): float {
        $subtotal = 0.0;

        foreach ($this->tools as $i => $tool) {
            $precioDia = $tool->getPrecioDia();
            $cantidad = $this->cantidades[$i];
            $dias = $this->dias[$i];

            $importe = $precioDia * $cantidad * $dias;

            if ($dias >= 7) {
                $importe *= 0.90; 
            }

            $subtotal += $importe;
        }

        return $subtotal;
    }

    public function calcularIVA(): float {
        return $this->calcularSubtotal() * 0.21;
    }

    public function calcularTotal(): float {
        return $this->calcularSubtotal() + $this->calcularIVA();
    }

    public function mostrarResumen(): void {
        echo "<h2>Resumen de Alquiler</h2>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>SKU</th><th>Nombre</th><th>Cant.</th><th>Días</th><th>Precio/Día (€)</th><th>Importe (€)</th></tr>";

        foreach ($this->tools as $i => $tool) {
            $precioDia = $tool->getPrecioDia();
            $cantidad = $this->cantidades[$i];
            $dias = $this->dias[$i];

            $importe = $precioDia * $cantidad * $dias;
            if ($dias >= 7) $importe *= 0.90;

            echo "<tr>";
            echo "<td>{$tool->getSku()}</td>";
            echo "<td>{$tool->getNombre()}</td>";
            echo "<td>$cantidad</td>";
            echo "<td>$dias</td>";
            echo "<td>" . number_format($precioDia, 2) . "</td>";
            echo "<td>" . number_format($importe, 2) . "</td>";
            echo "</tr>";
        }

        $subtotal = $this->calcularSubtotal();
        $iva = $this->calcularIVA();
        $total = $this->calcularTotal();

        echo "<tr><td colspan='5' align='right'><strong>Subtotal</strong></td><td>" . number_format($subtotal, 2) . "</td></tr>";
        echo "<tr><td colspan='5' align='right'><strong>IVA 21%</strong></td><td>" . number_format($iva, 2) . "</td></tr>";
        echo "<tr><td colspan='5' align='right'><strong>Total</strong></td><td>" . number_format($total, 2) . "</td></tr>";
        echo "</table>";
    }
}

?>
