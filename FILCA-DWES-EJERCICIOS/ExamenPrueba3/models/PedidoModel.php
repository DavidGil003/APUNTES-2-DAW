<?php
class PedidoModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM pedidos ORDER BY fecha_pedido DESC");
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public function getByID(int $id): ?array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
        $consulta->execute([$id]);
        $resultado = $consulta->fetch();
        return $resultado ?: null;
    }

    public function getActivos(): array
{
    // Seleccionamos todos los datos del pedido (p.*)
    // Y añadimos el nombre del plato renombrándolo como 'nombre_plato'
    $sql = "SELECT 
                p.*, 
                pl.nombre as nombre_plato,
                pl.precio as precio_plato
            FROM pedidos p
            INNER JOIN platos pl ON p.plato_id = pl.id
            WHERE p.fecha_entrega IS NULL 
            ORDER BY p.fecha_pedido DESC";

    $consulta = $this->pdo->prepare($sql);
    $consulta->execute();
    return $consulta->fetchAll();
}

    public function getFinalizados(): array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM pedidos WHERE fecha_entrega IS NOT NULL ORDER BY fecha_entrega DESC");
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public function getPedidosPorPlato(int $platoId): array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM pedidos WHERE plato_id = ? AND fecha_entrega IS NULL");
        $consulta->execute([$platoId]);
        return $consulta->fetchAll();
    }

    public function create(array $data): bool
    {
        $consulta = $this->pdo->prepare(
            "INSERT INTO pedidos (plato_id, num_mesa, cantidad, nombre_cliente, fecha_pedido, total) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        return $consulta->execute([
            $data['plato_id'],
            $data['num_mesa'],
            $data['cantidad'],
            $data['nombre_cliente'] ?? null,
            $data['fecha_pedido'],
            $data['total']
        ]);
    }

    public function marcarServido(int $id): bool
    {
        $consulta = $this->pdo->prepare("UPDATE pedidos SET fecha_entrega = NOW() WHERE id = ?");
        return $consulta->execute([$id]);
    }

    public function delete(int $id): bool
    {
        $consulta = $this->pdo->prepare("DELETE FROM pedidos WHERE id = ?");
        return $consulta->execute([$id]);
    }

    public function getTotalFacturado(): float
    {
        $consulta = $this->pdo->prepare("SELECT COALESCE(SUM(total), 0) as total FROM pedidos WHERE fecha_entrega IS NOT NULL");
        $consulta->execute();
        $resultado = $consulta->fetch();
        return (float) ($resultado['total'] ?? 0);
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
