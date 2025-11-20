<?php
abstract class Herramienta {
    private string $SKU;
    private string $nombre;
    private string $tipo;
    private float $precioDia;
    private string $descripcion;

	public function __construct(string $SKU, string $nombre, string $tipo, float $precioDia, string $descripcion) {
		$this->SKU = $SKU;
		$this->nombre = $nombre;
		$this->tipo = $tipo;
		$this->precioDia = $precioDia;
		$this->descripcion = $descripcion;
	}

    abstract public function calcularPrecio(int $dias, float $precioDia): float;
        

	public function getSKU() : string {
		return $this->SKU;
	}

	public function setSKU(string $value) {
		$this->SKU = $value;
	}

	public function getNombre() : string {
		return $this->nombre;
	}

	public function setNombre(string $value) {
		$this->nombre = $value;
	}

	public function getTipo() : string {
		return $this->tipo;
	}

	public function setTipo(string $value) {
		$this->tipo = $value;
	}

	public function getPrecioDia() : float {
		return $this->precioDia;
	}

	public function setPrecioDia(float $value) {
		$this->precioDia = $value;
	}

	public function getDescripcion() : string {
		return $this->descripcion;
	}

	public function setDescripcion(string $value) {
		$this->descripcion = $value;
	}

    public function __tostring() {
        return "({$this -> SKU}, {$this -> nombre}, {$this -> tipo}, {$this -> precioDia}, {$this -> descripcion})";
    }
}