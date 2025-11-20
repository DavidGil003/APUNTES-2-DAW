<?php
declare(strict_types=1);
session_start();

// Verificar que existe sesión activa
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Procesar logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

require_once "config/Conexion.php";
require_once "controllers/PedidoController.php";

$conexion = new Conexion('localhost', 'restaurantedb', 'root', '');
$pdo = $conexion->getPdo();

$controladorPedidos = new PedidoController($pdo);

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'create':
        $controladorPedidos->create();
        break;
    case 'marcar-servido':
        $controladorPedidos->marcarServido();
        break;
    case 'finalizados':
        $controladorPedidos->finalizados();
        break;
    case 'delete':
        $controladorPedidos->delete();
        break;
    case 'list':
    default:
        $controladorPedidos->index();
        break;
}
?>
