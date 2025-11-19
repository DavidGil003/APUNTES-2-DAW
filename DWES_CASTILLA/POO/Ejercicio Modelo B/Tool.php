<?php 

abstract class Tool {

    protected string $sku;
    protected string $nombre;
    protected float $precioDia;
    protected string $descripcion;

    public function __construct(string $sku, string $nombre, float $precioDia, string $descripcion) {
        $this->sku = $sku;
        $this->nombre = $nombre;
        $this->precioDia = $precioDia;
        $this->descripcion = $descripcion;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getPrecioBase(): float {
        return $this->precioDia;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function validar(): bool {
        return !empty($this->sku) && 
               !empty($this->nombre) && 
               $this->precioDia > 0;
    }

    abstract public function getPrecioDia(): float;
}

?>