<?php
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Paso 3 — Controlador + Vistas</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <header>
        <h1>🎯 Paso 3 — Controlador + Vistas (MVC básico)</h1>
        <p class="note">Las vistas son ahora puras y el controlador orquesta modelo + vistas.
            Se implementa una estructura MVC básica donde cada componente tiene su responsabilidad específica.</p>
    </header>

    <div style="margin-bottom: 20px;">
        <a class="button btn-success" href="?action=add">
            <span>➕</span> Añadir Cliente
        </a>
    </div>

    <!-- Búsqueda rápida opcional -->
    <div style="margin-bottom: 15px;">
        <input type="text" id="quickSearch" placeholder="🔍 Buscar cliente..."
            style="max-width: 300px; padding: 8px 12px;">
    </div>