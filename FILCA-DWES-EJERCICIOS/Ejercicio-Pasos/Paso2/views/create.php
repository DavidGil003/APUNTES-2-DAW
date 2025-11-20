<?php
?>

<h2>➕ Agregar Nuevo Cliente</h2>
<p style="color: #64748b; margin-bottom: 20px;">Complete el formulario para registrar un nuevo cliente en el sistema.
</p>

<form method="post" action="?action=add">
    <label>
        Nombre *
        <input name="nombre" type="text" required placeholder="Ej: Juan Pérez">
    </label>

    <label>
        Email *
        <input name="email" type="email" required placeholder="Ej: juan@ejemplo.com">
    </label>

    <label>
        Teléfono
        <input name="telefono" type="text" placeholder="Ej: +34 600 000 000">
    </label>

    <label>
        Dirección
        <input name="direccion" type="text" placeholder="Ej: Calle Mayor 123, Madrid">
    </label>

    <div style="margin-top: 25px; display: flex; gap: 10px;">
        <button type="submit" class="btn-primary">💾 Crear Cliente</button>
        <a class="button" href="?action=list">❌ Cancelar</a>
    </div>
</form>