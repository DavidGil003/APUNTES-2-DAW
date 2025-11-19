<?php 

class PaisModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Obtener todos los paises */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM paises ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Buscar si existe un país por nombre */
    public function getByNombre(string $nombre)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM paises WHERE nombre = ?");
        $stmt->execute([$nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Crear un nuevo país */
    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO paises (nombre, capital) VALUES (?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['capital']
        ]);
    }

    /** Actualizar capital basándose en el nombre del país */
    public function updateCapital(string $nombre, string $capital): bool
    {
        $stmt = $this->pdo->prepare("UPDATE paises SET capital=? WHERE nombre=?");
        return $stmt->execute([$capital, $nombre]);
    }

    /** Vaciar toda la tabla */
    public function deleteAll(): bool
    {
        return $this->pdo->exec("DELETE FROM paises") !== false; // O usar TRUNCATE TABLE paises
    }
} 
?>