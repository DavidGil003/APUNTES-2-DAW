<?php

require_once "Task.php";

session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if (isset($_POST['marcar_completada']) && isset($_POST['tarea_index'])) {
    $index = intval($_POST['tarea_index']);
    if (isset($_SESSION['tasks'][$index])) {
        $_SESSION['tasks'][$index]->markCompleted();
    }
}

if (isset($_POST['desmarcar_completada']) && isset($_POST['tarea_index'])) {
    $index = intval($_POST['tarea_index']);
    if (isset($_SESSION['tasks'][$index])) {
        $_SESSION['tasks'][$index]->markUncompleted();
    }
}

if (isset($_POST['añadir_tarea'])) {
    if (isset($_POST['nombre_tarea_usuario']) && !empty(trim($_POST['nombre_tarea_usuario']))) {

        $title = htmlspecialchars(trim($_POST['nombre_tarea_usuario']));
        $description = isset($_POST['descripcion_tarea']) ? htmlspecialchars(trim($_POST['descripcion_tarea'])) : '';

        $filePath = "";
        $fileName = "";
        $destPath = "";
        $newFileName = "";

        if (isset($_FILES["archivo_tarea"]) && $_FILES["archivo_tarea"]["error"] === UPLOAD_ERR_OK) {
            $maxSize = 5 * 1024 * 1024;

            $filePath = $_FILES["archivo_tarea"]["tmp_name"];
            $fileName = $_FILES["archivo_tarea"]["name"];
            $fileSize = $_FILES["archivo_tarea"]["size"];
            $fileType = $_FILES["archivo_tarea"]["type"];

            $allowedMimeTypes = [
                'image/jpeg',
                'image/png',
                'application/pdf',
                'text/plain',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ["jpeg", "png", "pdf", "plain", "docx"];

            if (in_array($fileType, $allowedMimeTypes) && in_array($fileExtension, $allowedExtensions)) {
                if ($fileSize <= $maxSize) {
                    $uploadDir = 'uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $newFileName = uniqid('archivo_tarea_', true) . '.' . $fileExtension;
                    $destPath = $uploadDir . $newFileName;

                    move_uploaded_file($filePath, $destPath);
                }
            }
        }

        $tarea = new Task($title, $description, false, $destPath, $newFileName, $fileName);

        $_SESSION['tasks'][] = $tarea;
    }
}

include 'head.php';

include 'body.php';

include 'foot.php';

?>