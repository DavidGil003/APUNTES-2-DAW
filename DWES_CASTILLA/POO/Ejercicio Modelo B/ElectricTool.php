<?php 

require_once 'Tool.php';

class ElectricTool extends Tool {
    
    private string $potencia;

    public function __construct(
        string $sku, 
        string $nombre, 
        float $precioDia, 
        string $descripcion,
        string $potencia
    ) {
        parent::__construct($sku, $nombre, $precioDia, $descripcion);
        $this->potencia = $potencia;
    }

    public function getPrecioDia(): float {
       
        return $this->precioDia * 1.25;
    }

    public function getPotencia(): string {
        return $this->potencia;
    }
}

?>