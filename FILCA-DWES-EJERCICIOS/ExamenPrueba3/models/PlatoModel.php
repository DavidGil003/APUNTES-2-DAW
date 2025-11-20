<?php
class PlatoModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM platos");
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public function getByID(int $id): ?array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM platos WHERE id = ?");
        $consulta->execute([$id]);
        $resultado = $consulta->fetch();

        return $resultado ?: null;
    }

    public function create(array $data): bool
    {
        $consulta = $this->pdo->prepare("INSERT INTO platos (nombre, categoria, precio, descripcion, foto) VALUES (?, ?, ?, ?, ?)");

        return $consulta->execute([
            $data['nombre'],
            $data['categoria'],
            $data['precio'],
            $data['descripcion'],
            $data['foto']
        ]);
    }

    public function update(int $id, array $data): bool
    {

        $consulta = $this->pdo->prepare("UPDATE platos SET nombre=?, categoria=?, precio=?, descripcion=?, foto=? WHERE id=?");

        return $consulta->execute([
            $data['nombre'],
            $data['categoria'],
            $data['precio'],
            $data['descripcion'],
            $data['foto'],
            $id
        ]);
    }

    public function delete(int $id) : bool {
        $consulta = $this->pdo->prepare("DELETE FROM platos WHERE id=?");

        return $consulta->execute([$id]);
    }

    public function getEstadisticasPlatos(): array
    {
        $consulta = $this->pdo->prepare("
            SELECT 
                p.id,
                p.nombre,
                p.categoria,
                p.precio,
                COUNT(pd.id) as total_pedidos,
                COALESCE(SUM(pd.total), 0) as ingresos
            FROM platos p
            LEFT JOIN pedidos pd ON p.id = pd.plato_id AND pd.fecha_entrega IS NOT NULL
            GROUP BY p.id
            ORDER BY p.nombre
        ");
        $consulta->execute();
        return $consulta->fetchAll();
    }
}
