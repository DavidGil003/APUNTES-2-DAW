<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <strong>❌ Error:</strong> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="form-container">
    <h2>➕ Registrar Nuevo Pedido</h2>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">Complete el formulario para registrar un nuevo pedido</p>

    <form method="POST" action="pedidos.php?action=create">
        <div class="form-grid">
            <div class="form-group">
                <label for="plato_id">Plato:</label>
                <select name="plato_id" id="plato_id" required>
                    <option value="">-- Selecciona un plato --</option>
                    <?php foreach ($platos as $plato): ?>
                        <option value="<?= $plato['id'] ?>">
                            <?= htmlspecialchars($plato['nombre']) ?> - €<?= number_format($plato['precio'], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="num_mesa">Número de Mesa (1-20):</label>
                <input type="number" id="num_mesa" name="num_mesa" min="1" max="20" required>
            </div>

            <div class="form-group">
                <label for="cantidad">Cantidad (1-10):</label>
                <input type="number" id="cantidad" name="cantidad" min="1" max="10" value="1" required>
            </div>

            <div class="form-group full-width">
                <label for="nombre_cliente">Nombre del Cliente (Opcional):</label>
                <input type="text" id="nombre_cliente" name="nombre_cliente" placeholder="EJ: Juan García">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">✅ Registrar Pedido</button>
            <a href="index.php" class="btn btn-secondary">📋 Ver Platos</a>
        </div>
    </form>
</div>