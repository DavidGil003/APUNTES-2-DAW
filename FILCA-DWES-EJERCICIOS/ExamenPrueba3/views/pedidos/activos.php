<div class="table-container" style="margin-top: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2>📋 Pedidos Activos</h2>
        <a href="exportar.php?tipo=pedidos" class="btn btn-secondary">📊 Exportar CSV</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mesa</th>
                <th>Plato</th>
                <th>Cantidad</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Fecha/Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pedidosActivos)): ?>
                <tr>
                    <td colspan="8" class="empty-message">
                        ✅ No hay pedidos pendientes
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($pedidosActivos as $pedido): ?>
                    <tr>
                        <td><strong>#<?= $pedido['id'] ?></strong></td>
                        <td><strong>Mesa <?= $pedido['num_mesa'] ?></strong></td>
                        <td><?= htmlspecialchars($pedido['nombre_plato']) ?></td>
                        <td><?= $pedido['cantidad'] ?></td>
                        <td><?= $pedido['nombre_cliente'] ? htmlspecialchars($pedido['nombre_cliente']) : '-' ?></td>
                        <td><strong>€<?= number_format($pedido['total'], 2) ?></strong></td>
                        <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
                        <td>
                            <form action="pedidos.php?action=marcar-servido" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                                <button type="submit" class="action-btn btn-edit">
                                    <span class="btn-icon">✅</span> Servido
                                </button>
                            </form>
                            <form action="pedidos.php?action=delete" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('¿Eliminar pedido?');">
                                    <span class="btn-icon">🗑️</span> Borrar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
