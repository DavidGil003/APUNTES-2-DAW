<?php
function crearSlug(string $titulo): string {
    $slug = strtolower($titulo);
    $slug = str_replace(' ', '-', $slug);
    $slug = preg_replace('/[^a-z0-9-]+/', '', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

$titulo = $_POST['titulo'] ?? '';
$slug = $titulo ? crearSlug($titulo) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado del Slug</title>
</head>
<body>
    <h1>Resultado del Slug</h1>
    <?php if ($titulo): ?>
        <p><strong>Título original:</strong> <?php echo htmlspecialchars($titulo); ?></p>
        <p><strong>Slug generado:</strong> <?php echo htmlspecialchars($slug); ?></p>
    <?php else: ?>
        <p>No se recibió ningún título.</p>
    <?php endif; ?>
    <a href="slug_form.html">Volver</a>
</body>
</html>