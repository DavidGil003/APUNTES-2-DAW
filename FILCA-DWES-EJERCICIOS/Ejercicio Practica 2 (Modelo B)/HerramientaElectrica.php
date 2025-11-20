<?php
require_once 'Herramienta.php';

class HerramientaElectrica extends Herramienta {
    public function calcularPrecio(int $dias, float $precioDia): float {
        $recargoPrecio = $precioDia * 0.25;

        $precioDia += $recargoPrecio;
        
        $precioTotalHerramienta = $dias * $precioDia;

        if ($dias >= 7) {
            $descuentoAplicar = $precioTotalHerramienta * 0.1;

            $precioTotalHerramienta -= $descuentoAplicar;
        }

        return $precioTotalHerramienta;
    }
}
?>