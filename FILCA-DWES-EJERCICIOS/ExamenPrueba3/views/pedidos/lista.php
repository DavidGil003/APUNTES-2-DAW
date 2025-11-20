<div class="table-container">
    <h2>Platos listados</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Disponible</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($platos)): ?>
                <tr>
                    <td colspan="7" class="empty-message">
                        No hay platos registrados en el sistema.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($platos as $plato): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($plato['id']) ?></strong></td>
                        <td class="foto-cell">
                            <?php if (!empty($plato['foto'])): ?>
                                <img src="<?= htmlspecialchars(platoController::obtenerUrlImagen($plato['foto'])) ?>" alt="<?= htmlspecialchars($plato['nombre']) ?>" class="foto-img">
                            <?php else: ?>
                                <span class="no-foto">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($plato['nombre']) ?></td>
                        <td><?= htmlspecialchars($plato['categoria']) ?></td>
                        <td><?= htmlspecialchars($plato['precio']) ?></td>
                        <td><?= htmlspecialchars($plato['descripcion']) ?></td>
                        <td><a href="?action=edit&id=<?= $plato['id'] ?>" class="action-btn btn-edit">
                                <span class="btn-icon">✏️</span> Editar
                            </a>
                            <form action="index.php?action=delete&id=<?= $plato['id'] ?>" method="POST" style="display:inline;">
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('¿Estás seguro de que quieres borrar este plato?');">
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