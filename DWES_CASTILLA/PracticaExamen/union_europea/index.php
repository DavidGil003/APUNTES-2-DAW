<?php
require_once 'db.php';
require_once 'models/PaisModel.php';

// Instanciar modelo
$model = new PaisModel($pdo);

// Variables para la vista
$paises = [];
$mensaje = "";

// --- LÓGICA DEL FORMULARIO (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. CASO: VACIAR LISTADO
    if (isset($_POST['vaciar'])) {
        $model->deleteAll();
        header('Location: index.php');
        exit;
    }

    // 2. CASO: AÑADIR O ACTUALIZAR PAÍS
    if (isset($_POST['add'])) {
        $nombre = trim($_POST['nombre'] ?? '');
        $capital = trim($_POST['capital'] ?? '');

        // Si campos vacíos -> No hacer nada (según PDF)
        if (!empty($nombre) && !empty($capital)) {
            
            // Comprobar si el país ya existe
            $paisExistente = $model->getByNombre($nombre);

            if ($paisExistente) {
                // Si existe y la capital es diferente, actualizamos
                if ($paisExistente['capital'] !== $capital) {
                    $model->updateCapital($nombre, $capital);
                }
            } else {
                // Si no existe, lo creamos
                $model->create([
                    'nombre' => $nombre,
                    'capital' => $capital
                ]);
            }
            // Patrón PRG (Post-Redirect-Get) para evitar reenvío al refrescar
            header('Location: index.php');
            exit;
        }
    }
}

// --- OBTENER DATOS PARA LA VISTA (GET) ---
$paises = $model->getAll();

// --- CARGAR VISTAS ---
require_once 'Views/header.php';
// Pasamos $paises a la vista form.php
include 'Views/paises/form.php'; 
require_once 'Views/footer.php';
?>