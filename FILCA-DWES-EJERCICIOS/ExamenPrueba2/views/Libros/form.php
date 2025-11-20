<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <strong>❌ Error:</strong> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php
$isEditando = ($action === 'edit' && isset($libro));
$titulo = $isEditando ? "Modificar Libro #" . htmlspecialchars($libro['id']) : "Agregar nuevo libro";
$descripcion = $isEditando ? "Actualice la información del libro." : "Complete el formulario para registrar un nuevo libro en el sistema.";
$actioUrl = $isEditando ? "index.php?action=edit&id=" . $libro['id'] : "index.php?action=add";
$botonTexto = $isEditando ? "✅ Guardar Cambios" : "✅ Guardar Libro";
?>




<form method="POST" action="<?= $actioUrl ?>" enctype="multipart/form-data" class="form-container">
    <?php if ($isEditando): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($libro['id']) ?>">
    <?php endif; ?>

    <h2><?= $titulo ?></h2>
    <p><?= $descripcion ?></p>
    <br>

    <div class="form-group">
        <label for="titulo">Título:</label>
        <input name="titulo" id="titulo" type="text" required
            value="<?= $isEditando ? htmlspecialchars($libro['titulo']) : '' ?>"
            placeholder="<?= $isEditando ? '' : 'Ej: EL ÚLTIMO SECRETO' ?>">
    </div>

    <div class="form-group">
        <label for="autor">Autor:</label>
        <input name="autor" id="autor" type="text" required
            value="<?= $isEditando ? htmlspecialchars($libro['autor']) : '' ?>"
            placeholder="<?= $isEditando ? '' : 'Ej: Dan Brown' ?>">
    </div>

    <div class="form-group">
        <label for="portada">Portada (imagen):</label>
        
        <?php if ($isEditando && !empty($libro['portada'])): ?>
            <div style="margin-bottom: 15px;">
                <img src="<?= htmlspecialchars(LibroController::obtenerUrlImagen($libro['portada'])) ?>" alt="Portada actual" style="max-width: 150px; max-height: 200px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                <p style="margin-top: 10px; font-size: 0.9rem; color: #94a3b8;">Imagen actual</p>
            </div>
        <?php endif; ?>
        
        <input name="portada" id="portada" type="file" accept="image/jpeg,image/png">
        <small>Formatos permitidos: JPG, PNG (máx 2MB)</small>
        <?php if ($isEditando): ?>
            <small style="display: block; margin-top: 8px; color: #94a3b8;">Deja este campo vacío para mantener la imagen actual</small>
        <?php endif; ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $botonTexto ?></button>
        <a href="index.php?action=list" class="btn btn-secondary">❌ Cancelar</a>
    </div>
</form>