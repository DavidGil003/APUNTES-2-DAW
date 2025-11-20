<?php
session_start();

// Obtener tema actual
$tema_actual = $_COOKIE['tema'] ?? 'dark';

// Cambiar al tema opuesto
$nuevo_tema = ($tema_actual === 'dark') ? 'light' : 'dark';

// Guardar en cookie por 30 días (30 * 24 * 60 * 60 = 2592000 segundos)
setcookie('tema', $nuevo_tema, time() + 2592000, '/');

// Redirigir a la página anterior o a la lista
$referrer = $_SERVER['HTTP_REFERER'] ?? 'index.php?action=list';
header('Location: ' . $referrer);
exit;
?>
