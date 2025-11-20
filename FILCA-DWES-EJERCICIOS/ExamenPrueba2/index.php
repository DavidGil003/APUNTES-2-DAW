<?php 
declare(strict_types=1);
session_start();
require_once "db/Conexion.php";
require_once "controllers/LibroController.php";

$conexion = new Conexion('localhost', 'biblioteca', 'root', '');
$pdo = $conexion->getPdo();

$controladorLibros = new LibroController($pdo);

require_once "templates/header.php";

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
        require_once "models/LibroModel.php";
        $model = new LibroModel($pdo);
        $libros = $model->getAll();

        require_once "templates/body.php";

        break;
}

require_once "templates/footer.php";

?>