<?php

require_once 'db/Conexion.php';

class PaisModel
{
    private Conexion $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion('127.0.0.1', 'unioneuropea', 'root', '');
    }

    public function getAll(): array
    {
        return $this->conexion->query('SELECT * FROM paises');
    }

    public function existePais(string $nombre): ?array
    {
        $resultado = $this->conexion->query('SELECT * FROM paises WHERE nombre = ?', [$nombre]);
        return !empty($resultado) ? $resultado[0] : null;
    }

    public function insertarPais(string $nombre, string $capital): bool
    {
        try {
            return $this->conexion->execute('INSERT INTO paises (nombre, capital) VALUES (?, ?)', [$nombre, $capital]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function actualizarCapital(string $nombre, string $capital): bool
    {
        try {
            return $this->conexion->execute('UPDATE paises SET capital = ? WHERE nombre = ?', [$capital, $nombre]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function vaciarListado(): bool
    {
        try {
            return $this->conexion->execute('DELETE FROM paises');
        } catch (Exception $e) {
            return false;
        }
    }
}