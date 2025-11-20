<?php
// Determinar tema actual
$tema = $_COOKIE['tema'] ?? 'dark';
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Biblioteca - Gestor de Libros</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body data-tema="<?= htmlspecialchars($tema) ?>">
    <div class="container">
        <header>
            <div class="header-content">
                <h1>📚 Biblioteca de Filca</h1>
                <button id="btnCambiarTema" class="btn-cambiar-tema" title="Cambiar Tema">
                    <span class="icon-tema"><?= $tema === 'dark' ? '☀️' : '🌙' ?></span>
                </button>
            </div>
        </header>

        <?php if (!empty($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['tipo_mensaje'] ?? 'info') ?>">
                ✅ <?= htmlspecialchars($_SESSION['mensaje']) ?>
            </div>
            <?php 
                // Limpiar el mensaje después de mostrarlo
                unset($_SESSION['mensaje']);
                unset($_SESSION['tipo_mensaje']);
            ?>
        <?php endif; ?>