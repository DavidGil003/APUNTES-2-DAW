<?php

class Alumno
{



    public function __construct(
        private string $nombre,
        private string $fechaNacimiento,
        private string $notas,
        private string $comportamiento,
        private string $comentario
    ) {
    }




    public function getNombre(): string
    {
        return $this->nombre;

    }

    public function getFechaNacimiento(): string
    {
        return $this->fechaNacimiento;
    }
    public function getNotas(): string
    {
        return $this->notas;
    }
    public function getComportamiento(): string
    {
        return $this->comportamiento;

    }
    public function getComentario(): string
    {
        return $this->comentario;
    }


    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
    public function setFechaNacimiento(string $fecha)
    {

        $this->fechaNacimiento = $fecha;
    }

    public function setNotas(string $notas)
    {
        $this->notas = $notas;
    }

    public function setComportamiento(string $comportamiento)
    {
        $this->comportamiento = $comportamiento;
    }

    public function setComentario(string $comentario)
    {

        $this->comentario = $comentario;


    }




    public function calcularNota(string $notas, string $comportamiento): float
    {
        $arrayNotas = [];
        $media = 0;

        $notasdef = explode("|", $notas);
        foreach ($notasdef as $n) {
            $arrayNotas[] = (float) trim($n);
        }






        return $media = $this->calcularMedia($arrayNotas, $comportamiento);
    }

    public function calcularMedia(array $notas, string $comportamiento): float
    {

        $notaFinal = array_sum($notas);


       

        $media = $notaFinal / sizeof($notas);

         switch (strtolower($comportamiento)) {

            case "muy malo":
                $media -= 2;
                break;
            case "malo":
                 $media -= 1;
                break;
            case "normal":
                $media -= 0;
                break;
            case "bueno":
               $media += 1;
                break;
            case "excelente":
               $media  += 2;
                break;
        }


        return $media;





    }


    public function estaSupendido(float $media): string
    {
        $estado = "";

        if ($media > 5) {

            $estado = "aprobado";

        } else if ($media == 5) {

            $estado = "aprobado por los pelos";
        } else {

            $estado = "suspendido";
        }

        return $estado;


    }


public function __toString(): string
{
    $media = $this->calcularNota($this->notas, $this->comportamiento);
    $estado = $this->estaSupendido($media);
    return 
        "Nombre: {$this->nombre}\n" .
        "Notas: {$this->notas}\n" .
        "Comportamiento: {$this->comportamiento}\n" .
        "Media: " . number_format($media, 1) . "\n" .
        "Estado: $estado\n";
}



}






?>