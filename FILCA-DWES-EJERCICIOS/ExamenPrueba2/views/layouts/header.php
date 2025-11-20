<?php
$ultimaVisita = $_COOKIE['ultima_visita'] ?? 'Primera vez';
setcookie('ultima_visita', date('d/m/Y H:i:s'), time() + 3600 * 24 * 30, "/");
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Biblioteca - Gestor de Libros</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="public/css/styles.css">
</head>

<body>
    <div class="container">
        <header>
            <div class="header-content">
                <h1>📚 Biblioteca de Filca</h1>
                <span class="info-visita">🕒 Último acceso: <?= htmlspecialchars($ultimaVisita) ?></span>
            </div>
        </header>

        <?php if (!empty($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['tipo_mensaje'] ?? 'info') ?>">
                <?= ($_SESSION['tipo_mensaje'] == 'error') ? '❌' : '✅' ?>
                <?= htmlspecialchars($_SESSION['mensaje']) ?>
            </div>
            <?php
            // Limpiar el mensaje después de mostrarlo
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo_mensaje']);
            ?>
        <?php endif; ?>