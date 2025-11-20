<?php

class ClienteModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM clientes ORDER BY id DESC");
        $consulta->execute();
        return $consulta->fetchAll();
    }

    public function getByID(int $id): ?array
    {
        $consulta = $this->pdo->prepare("SELECT * FROM clientes WHERE id = ?");
        $consulta->execute([$id]);
        $r = $consulta->fetch();

        return $r ?: null;
    }

    public function create(array $data): bool
    {
        $consulta = $this->pdo->prepare("INSERT INTO clientes (nombre,email,telefono,direccion) VALUES (?,?,?,?)");

        return $consulta->execute([
            $data['nombre'] ?? '',
            $data['email'] ?? '',
            $data['telefono'] ?? null,
            $data['direccion'] ?? null
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE clientes SET nombre=?, email=?, telefono=?, direccion=? WHERE id=?");
        return $stmt->execute([
            $data['nombre'] ?? '',
            $data['email'] ?? '',
            $data['telefono'] ?? null,
            $data['direccion'] ?? null,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM clientes WHERE id =?");
        return $stmt->execute([$id]);
    }

}