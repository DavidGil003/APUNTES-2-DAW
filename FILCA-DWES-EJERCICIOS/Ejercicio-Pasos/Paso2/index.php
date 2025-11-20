<?php
declare(strict_types=1);

require_once "config/conexion.php";
require_once "models/ClienteModel.php";

$conexion = new Conexion('127.0.0.1', 'dwes_mvc_ej', 'root', '');
$pdo = $conexion->getPdo();

$modelo = new ClienteModel($pdo);

$action = $_REQUEST['action'] ?? 'list';

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'nombre'    => $_POST['nombre'] ?? '',
        'email'     => $_POST['email'] ?? '',
        'telefono'  => $_POST['telefono'] ?? '',
        'direccion' => $_POST['direccion'] ?? ''
    ];
    
    $modelo->create($datos);
    
    header('Location: index.php?action=list'); 
    exit;
}

if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $datos = [
        'nombre'    => $_POST['nombre'] ?? '',
        'email'     => $_POST['email'] ?? '',
        'telefono'  => $_POST['telefono'] ?? '',
        'direccion' => $_POST['direccion'] ?? ''
    ];

    $modelo->update($id, $datos);
    
    header('Location: index.php?action=list');
    exit;
}

if ($action === 'delete') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id) {
        $modelo->delete($id);
    }
    header('Location: index.php?action=list');
    exit;
}

$cliente = null;
if ($action === 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int) $_GET['id'];
    $cliente = $modelo->getByID($id);
}

$clientes = [];
if ($action === 'list') {
    $clientes = $modelo->getAll();
}

require "vista_lista_clientes.php";
?>