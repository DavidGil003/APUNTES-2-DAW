<?php
require __DIR__ . "/../../templates/header.php";

// Determinar si estamos creando o editando
$isEditing = ($action === 'edit' && isset($cliente));
$titulo = $isEditing ? "✏️ Modificar Cliente #" . htmlspecialchars($cliente['id']) : "➕ Agregar Nuevo Cliente";
$descripcion = $isEditing ? "Actualice la información del cliente seleccionado." : "Complete el formulario para registrar un nuevo cliente en el sistema.";
$actionUrl = $isEditing ? "?action=edit&id=" . $cliente['id'] : "?action=add";
$botonTexto = $isEditing ? "💾 Guardar Cambios" : "💾 Crear Cliente";
?>

<h2><?= $titulo ?></h2>
<p style="color: #64748b; margin-bottom: 20px;"><?= $descripcion ?></p>

<form method="post" action="<?= $actionUrl ?>">
    <?php if ($isEditing): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id']) ?>">
    <?php endif; ?>

    <label>
        Nombre *
        <input name="nombre" type="text" required 
               value="<?= $isEditing ? htmlspecialchars($cliente['nombre']) : '' ?>"
               placeholder="<?= $isEditing ? '' : 'Ej: Juan Pérez' ?>">
    </label>

    <label>
        Email *
        <input name="email" type="email" required 
               value="<?= $isEditing ? htmlspecialchars($cliente['email']) : '' ?>"
               placeholder="<?= $isEditing ? '' : 'Ej: juan@ejemplo.com' ?>">
    </label>

    <label>
        Teléfono
        <input name="telefono" type="text" 
               value="<?= $isEditing ? htmlspecialchars($cliente['telefono']) : '' ?>"
               placeholder="<?= $isEditing ? '' : 'Ej: +34 600 000 000' ?>">
    </label>

    <label>
        Dirección
        <input name="direccion" type="text" 
               value="<?= $isEditing ? htmlspecialchars($cliente['direccion']) : '' ?>"
               placeholder="<?= $isEditing ? '' : 'Ej: Calle Mayor 123, Madrid' ?>">
    </label>

    <div style="margin-top: 25px; display: flex; gap: 10px;">
        <button type="submit" class="btn-primary"><?= $botonTexto ?></button>
        <a class="button" href="?action=list">❌ Cancelar</a>
    </div>
</form>

<?php
require __DIR__ . "/../../templates/footer.php";
?>