<?php
class Alumno {
    private string $nombre;
    private string $fechaNacimiento;
    private array $notas;
    private string $comportamiento;
    private string $comentarios;

    public function __construct($nombre, $fechaNacimiento, $notas, $comportamiento) {
        $this -> nombre = $nombre;
        $this -> fechaNacimiento = $fechaNacimiento;
        $this -> notas = $notas;
        $this -> comportamiento = $comportamiento;
        $this -> comentarios = "";
    }

	public function getNombre() : string {
		return $this->nombre;
	}

	public function setNombre(string $value) {
		$this->nombre = $value;
	}

	public function getFechaNacimiento() : string {
		return $this->fechaNacimiento;
	}

	public function setFechaNacimiento(string $value) {
		$this->fechaNacimiento = $value;
	}

	public function getNotas() : array {
		return $this->notas;
	}

	public function setNotas(array $value) {
		$this->notas = $value;
	}

	public function getComportamiento() : string {
		return $this->comportamiento;
	}

	public function setComportamiento(string $value) {
		$this->comportamiento = $value;
	}

	public function getComentarios() : string {
		return $this->comentarios;
	}

	public function setComentarios(string $value) {
		$this->comentarios = $value;
	}

	public function __tostring(): string
	{
		$notasStr = implode("|", $this->notas);
		return "({$this -> nombre}, {$this -> fechaNacimiento}, {$notasStr}, {$this -> comportamiento})";
	}
}