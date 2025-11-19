<?php
require_once "Contact.php";
session_start();
//session_unset();


if (!isset($_SESSION['contacts']) || !is_array($_SESSION['contacts'])) {
    $_SESSION['contacts'] = [];
}


$success = null;
$error = null;
$errores = [];

$filteredContacts = $_SESSION['contacts'];


$q = '';
if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
}


$nombre_usuario = '';
$email_usuario = '';
$telefono_usuario = '';
$avatarPath = '';
$avatarName = '';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_contact'])) {

    $nombre_usuario = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email_usuario = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $telefono_usuario = trim(filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    if (empty($nombre_usuario)) {
        $errores[] = "El nombre es obligatorio.";
    }

    if (empty($email_usuario) || !filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del email no es válido.";
    }


    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['avatar'];
        $fileSize = $file['size'];
        $fileTmpName = $file['tmp_name'];
        $fileName = $file['name'];


        if ($fileSize > 2 * 1024 * 1024) {
            $errores[] = "El avatar no puede superar los 2MB.";
        }


        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileTmpName);
        finfo_close($finfo);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mimeType, $allowedTypes)) {
            $errores[] = "Solo se permiten imágenes JPG, PNG o GIF.";
        }


        if (empty($errores)) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid('avatar_') . '.' . $extension;
            $avatarPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpName, $avatarPath)) {
                $avatarName = $fileName;
            } else {
                $errores[] = "Error al subir el avatar.";
            }
        }
    }

    if (empty($errores)) {

        $nuevoContacto = new Contact(
            $nombre_usuario,
            $email_usuario,
            $telefono_usuario,
            $avatarPath,
            $avatarName
        );

        $_SESSION['contacts'][] = $nuevoContacto;


        $filteredContacts = $_SESSION['contacts'];

        $success = "✓ Contacto '{$nombre_usuario}' añadido correctamente";


        $nombre_usuario = '';
        $email_usuario = '';
        $telefono_usuario = '';
        $avatarPath = '';
        $avatarName = '';
    } else {
        $error = implode(" | ", $errores);
    }



}


if (isset($_GET['action']) && $_GET['action'] === 'toggle_fav' && isset($_GET['i'])) {
    $i = filter_var($_GET['i'], FILTER_VALIDATE_INT);
    if ($i !== false && isset($_SESSION['contacts'][$i])) {
    
        $_SESSION['contacts'][$i]->toggleFavorite();
     
        header('Location: index.php');
        exit;
    }
}


$filteredContacts = [];
if (isset($_GET['q']) && trim($_GET['q']) !== '') {
    $q = trim($_GET['q']);
    setcookie('last_search', $q, time() + 3600);
    foreach ($_SESSION['contacts'] as $idx => $contact) {
        if (stripos($contact->getName(), $q) !== false) {
            $filteredContacts[$idx] = $contact; 
            break; 
        }
    }
} elseif (!isset($_GET['q']) && isset($_COOKIE['last_search']) && trim($_COOKIE['last_search']) !== '') {
    $q = trim($_COOKIE['last_search']);
    foreach ($_SESSION['contacts'] as $idx => $contact) {
        if (stripos($contact->getName(), $q) !== false) {
            $filteredContacts[$idx] = $contact;
            break;
        }
    }
} else {

    $filteredContacts = $_SESSION['contacts'];
}

include 'head.php';
include 'body.php';
include 'foot.php';

?>