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
require_once "controllers/PlatoController.php";

$conexion = new Conexion('localhost', 'restaurantedb', 'root', '');
$pdo = $conexion->getPdo();

$controladorPlatos = new PlatoController($pdo);

require_once "views/layouts/header.php";

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'add':
        $controladorPlatos->add();
        break;
    case 'edit':
        $controladorPlatos->edit();
        break;
    case 'delete':
        $controladorPlatos->delete();
        break;
    case 'list':
    default:
        $controladorPlatos->index();
        break;
}

require_once "views/layouts/footer.php";

?>