<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <strong>❌ Error:</strong> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<?php
$isEditando = ($action === 'edit' && isset($plato));
$nombre = $isEditando ? "Modificar Plato #" . htmlspecialchars($plato['id']) : "Agregar nuevo plato";
$descripcion = $isEditando ? "Actualice la información del plato." : "Complete el formulario para registrar un nuevo plato en el sistema.";
$actioUrl = $isEditando ? "index.php?action=edit&id=" . $plato['id'] : "index.php?action=add";
$botonTexto = $isEditando ? "✅ Guardar Cambios" : "✅ Guardar plato";
?>

<form method="POST" action="<?= $actioUrl ?>" enctype="multipart/form-data" class="form-container">
    <?php if ($isEditando): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($plato['id']) ?>">
    <?php endif; ?>

    <h2><?= $nombre ?></h2>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;"><?= $descripcion ?></p>

    <div class="form-grid">
        
        <div class="form-group">
            <label for="nombre">Nombre del plato:</label>
            <input name="nombre" id="nombre" type="text" required
                value="<?= $isEditando ? htmlspecialchars($plato['nombre']) : '' ?>"
                placeholder="<?= $isEditando ? '' : 'EJ: Paella Valenciana' ?>">
        </div>

        <div class="form-group">
            <label for="categoria">Categoría:</label>
            <select name="categoria" id="categoria" required>
                <option value="">-- Selecciona --</option>
                <option value="Entrante" <?= $isEditando && $plato['categoria'] === 'Entrante' ? 'selected' : '' ?>>Entrante</option>
                <option value="Principal" <?= $isEditando && $plato['categoria'] === 'Principal' ? 'selected' : '' ?>>Principal</option>
                <option value="Postre" <?= $isEditando && $plato['categoria'] === 'Postre' ? 'selected' : '' ?>>Postre</option>
            </select>
        </div>

        <div class="form-group">
            <label for="precio">Precio (€):</label>
            <input name="precio" id="precio" type="number" step="0.01" min="0" required
                value="<?= $isEditando ? htmlspecialchars($plato['precio']) : '' ?>"
                placeholder="<?= $isEditando ? '' : 'EJ: 12.50' ?>">
        </div>

        <div class="form-group">
            <label for="foto">Foto del plato:</label>
            
            <?php if ($isEditando && !empty($plato['foto'])): ?>
                <div class="current-image-preview">
                    <img src="<?= htmlspecialchars(platoController::obtenerUrlImagen($plato['foto'])) ?>" 
                         alt="Actual" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Imagen actual guardada</span>
                </div>
            <?php endif; ?>

            <input name="foto" id="foto" type="file" accept="image/jpeg,image/png">
            <small style="font-size: 0.75rem;">JPG/PNG máx 2MB. <?= $isEditando ? '(Opcional)' : '' ?></small>
        </div>

        <div class="form-group full-width">
            <label for="descripcion">Descripción detallada:</label>
            <textarea name="descripcion" id="descripcion" required rows="4"
                placeholder="<?= $isEditando ? '' : 'Describe los ingredientes y la preparación...' ?>"><?= $isEditando ? htmlspecialchars($plato['descripcion']) : '' ?></textarea>
        </div>

    </div> 
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $botonTexto ?></button>
    </div>
</form>