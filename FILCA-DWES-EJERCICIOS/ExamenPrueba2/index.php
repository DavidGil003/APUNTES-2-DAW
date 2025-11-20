<?php
declare(strict_types=1);
session_start();
require_once "config/Conexion.php";
require_once "controllers/LibroController.php";

$conexion = new Conexion('localhost', 'biblioteca', 'root', '');
$pdo = $conexion->getPdo();

$controladorLibros = new LibroController($pdo);

require_once "views/layouts/header.php";

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'add':
        $controladorLibros->add();
        break;
    case 'edit':
        $controladorLibros->edit();
        break;
    case 'delete':
        $controladorLibros->delete();
        break;
    case 'list':
    default:
        $controladorLibros->index();
        break;
}

require_once "views/layouts/footer.php";

?>