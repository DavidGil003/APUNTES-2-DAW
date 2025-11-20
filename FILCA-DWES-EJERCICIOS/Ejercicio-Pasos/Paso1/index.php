<?php
declare(strict_types=1);

require_once "config/conexion.php";

// Instanciar la conexión usando tu clase
$conexion = new Conexion('127.0.0.1', 'dwes_mvc_ej', 'root', '');
$pdo = $conexion->getPdo();

$action = $_REQUEST['action'] ?? 'list';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre,email,telefono,direccion)
VALUES (?,?,?,?)");
    $stmt->execute([$nombre, $email, $telefono, $direccion]);
    // PRG (Post-Redirect-Get) sería mejor, pero para simplicidad actualizamos
    $action = 'list';
}
// EDIT: actualizar cliente
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $stmt = $pdo->prepare("UPDATE clientes SET nombre=?, email=?, telefono=?,
direccion=? WHERE id=?");
    $stmt->execute([$nombre, $email, $telefono, $direccion, $id]);
    $action = 'list';
}
// DELETE: borrar cliente (por GET en este ejemplo; en producción usar POST)
if ($action === 'delete') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id=?");
        $stmt->execute([$id]);
    }
    $action = 'list';
}
// Obtener datos para editar (cuando accedemos a ?action=edit&id=...)
$cliente = null;
if (
    $action === 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] ===
    'GET'
) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id=?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}

require "vista_lista_clientes.php";
?>