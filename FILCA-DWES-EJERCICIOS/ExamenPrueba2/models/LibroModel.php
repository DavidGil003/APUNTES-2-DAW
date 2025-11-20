<?php
class LibroModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM libros");
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public function getByID(int $id): ?array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM libros WHERE id = ?");
        $consulta->execute([$id]);
        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    public function create(array $data): bool
    {
        if (empty($data['titulo']) || empty($data['autor'])) {
            throw new Exception("El titulo y el autor son campos obligatorios");
        }

        $consulta = $this->pdo->prepare("INSERT INTO libros (titulo, autor, portada) VALUES (?, ?, ?)");

        return $consulta->execute([
            $data['titulo'],
            $data['autor'],
            $data['portada_ruta']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        if (empty($data['titulo']) || empty($data['autor'])) {
            throw new Exception("El titulo y el autor son campos obligatorios");
        }

        $consulta = $this->pdo->prepare("UPDATE libros SET titulo=?, autor=?, portada=? WHERE id=?");

        return $consulta->execute([
            $data['titulo'],
            $data['autor'],
            $data['portada'] ?? null,
            $id
        ]);
    }

    public function delete(int $id) : bool {
        $consulta = $this->pdo->prepare("DELETE FROM libros WHERE id=?");

        return $consulta->execute([$id]);
    }
}
