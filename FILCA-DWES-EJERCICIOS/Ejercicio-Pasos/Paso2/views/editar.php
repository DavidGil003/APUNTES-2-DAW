<?php
?>

<h2>✏️ Modificar Cliente #<?= htmlspecialchars($cliente['id']) ?></h2>
<p style="color: #64748b; margin-bottom: 20px;">Actualice la información del cliente seleccionado.</p>

<form method="post" action="?action=edit">
    <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id']) ?>">

    <label>
        Nombre *
        <input name="nombre" type="text" required value="<?= htmlspecialchars($cliente['nombre']) ?>">
    </label>

    <label>
        Email *
        <input name="email" type="email" required value="<?= htmlspecialchars($cliente['email']) ?>">
    </label>

    <label>
        Teléfono
        <input name="telefono" type="text" value="<?= htmlspecialchars($cliente['telefono']) ?>">
    </label>

    <label>
        Dirección
        <input name="direccion" type="text" value="<?= htmlspecialchars($cliente['direccion']) ?>">
    </label>

    <div style="margin-top: 25px; display: flex; gap: 10px;">
        <button type="submit" class="btn-primary">💾 Guardar Cambios</button>
        <a class="button" href="?action=list">❌ Cancelar</a>
    </div>
</form>