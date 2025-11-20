<?php
require_once "Herramienta.php";

class HerramientaManual extends Herramienta {
    public function calcularPrecio(int $dias, float $precioDia): float {
        $precioTotalHerramienta = $dias * $precioDia;

        if ($dias >= 7) {
            $descuentoAplicar = $precioTotalHerramienta * 0.1;

            $precioTotalHerramienta -= $descuentoAplicar;
        }

        return $precioTotalHerramienta;
    }
}
?>