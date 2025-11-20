<?php
require_once "models/PaisModel.php";

$paisModel = new PaisModel();
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        switch ($_POST['accion']) {
            case 'add':
                $pais = trim($_POST['pais'] ?? '');
                $capital = trim($_POST['capital'] ?? '');
                
                if (!empty($pais) && !empty($capital)) {
                    $paisExistente = $paisModel->existePais($pais);
                    
                    if ($paisExistente) {
                        if ($paisExistente['capital'] !== $capital) {
                            if ($paisModel->actualizarCapital($pais, $capital)) {
                                $mensaje = "Capital de $pais actualizada correctamente.";
                            } else {
                                $mensaje = "Error al actualizar la capital.";
                            }
                        } else {
                            $mensaje = "El país $pais ya existe con esa capital.";
                        }
                    } else {
                        if ($paisModel->insertarPais($pais, $capital)) {
                            $mensaje = "País $pais añadido correctamente.";
                        } else {
                            $mensaje = "Error al añadir el país.";
                        }
                    }
                }
                break;
                
            case 'vaciar':
                if ($paisModel->vaciarListado()) {
                    $mensaje = "Listado de países vaciado correctamente.";
                } else {
                    $mensaje = "Error al vaciar el listado.";
                }
                break;
        }
    }
}

$paises = $paisModel->getAll();

include "templates/body.php";
?>