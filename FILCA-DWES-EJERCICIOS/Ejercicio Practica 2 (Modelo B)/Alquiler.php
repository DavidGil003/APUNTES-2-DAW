<?php
require_once "HerramientaElectrica.php";
require_once "HerramientaManual.php";

class Alquiler
{
    private array $herramientas;


    public function __construct(array $herramientas)
    {

        $this->herramientas = $herramientas;
    }

    private function separarArray($herramientas): array
    {
        $herramientasDivido = [];

        foreach ($herramientas as $herramienta) {
            $herramientasDivido[] = explode(':', $herramienta);
        }

        return $herramientasDivido;
    }

    public function calcularTotal(): array
    {
        $subtotal = 0;
        $arrayInformacionHerramienta = $this->separarArray($this->herramientas);


        for ($i = 0; $i < count($arrayInformacionHerramienta); $i++) {
            $SKU = $arrayInformacionHerramienta[$i][0];
            $nombre = $arrayInformacionHerramienta[$i][1];
            $precioDia = $arrayInformacionHerramienta[$i][2];
            $cantidad = $arrayInformacionHerramienta[$i][3];
            $diasAlquiler = $arrayInformacionHerramienta[$i][4];

            $tipoHerramienta = $arrayInformacionHerramienta[$i][5];

            $descripcionHerramienta = $arrayInformacionHerramienta[$i][6];

            switch ($tipoHerramienta) {
                case "E":
                    $herramientaElectrica = new HerramientaElectrica($SKU, $nombre, $tipoHerramienta, $precioDia, $descripcionHerramienta);
                    $subtotal += $herramientaElectrica->calcularPrecio($diasAlquiler, $precioDia) * $cantidad;
                    break;
                case "M":
                    $herramientaManual = new HerramientaManual($SKU, $nombre, $tipoHerramienta, $precioDia, $descripcionHerramienta);
                    $subtotal += $herramientaManual->calcularPrecio($diasAlquiler, $precioDia) * $cantidad;
                    break;
            }
        }

        $iva = $subtotal * 0.21;
        
        $totalAlquiler = $subtotal + $iva;

        return [
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $totalAlquiler
        ];
    }

    public function getHerramientas(): array
    {
        return $this->herramientas;
    }

    public function setHerramientas(array $value)
    {
        $this->herramientas = $value;
    }

    public function __toString(): string
    {
        $resultado = "Alquiler de herramientas: <br>";
        foreach ($this->herramientas as $herramienta) {
            $resultado .= "- " . $herramienta . "<br>";
        }
        return $resultado;
    }
}
