<?php

class Conexion
{
    private PDO $pdo;

    public function __construct(string $host, string $dbName, string $user, string $pass)
    {
        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            require_once __DIR__ . "/../views/layouts/die.php";
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
?>