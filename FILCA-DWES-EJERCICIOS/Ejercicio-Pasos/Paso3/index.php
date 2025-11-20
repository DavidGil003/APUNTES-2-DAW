<?php
declare(strict_types=1);

require_once "config/conexion.php";
require_once "controllers/ClienteController.php";

// Crear conexión a la base de datos
$conexion = new Conexion('localhost', 'dwes_mvc_ej', 'root', '');
$pdo = $conexion->getPdo();

$controlador = new ClienteController($pdo);

$action = $_GET['action'] ?? 'list';

switch ($action) {
    case 'add':
        $controlador->add();
        break;
    case 'edit':
        $controlador->edit();
        break;
    case 'delete':
        $controlador->delete();
        break;
    case 'list':
    default:
        // Para mostrar la lista, necesitamos obtener los datos y mostrar las vistas
        require_once "models/ClienteModel.php";
        $model = new ClienteModel($pdo);
        $clientes = $model->getAll();

        require_once "templates/body.php";

        break;
}
?>