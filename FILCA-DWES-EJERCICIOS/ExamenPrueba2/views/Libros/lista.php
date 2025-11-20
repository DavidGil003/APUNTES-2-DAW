<div class="table-container">
    <h2>Libros listados</h2>
    <table class="libros-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Portada</th>
                <th>Título</th>
                <th>Autor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($libros)): ?>
                <tr>
                    <td colspan="4" class="empty-message">
                        📋 No hay libros registrados en el sistema.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($libros as $libro): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($libro['id']) ?></strong></td>
                        <td class="portada-cell">
                            <?php if (!empty($libro['portada'])): ?>
                                <img src="<?= htmlspecialchars(LibroController::obtenerUrlImagen($libro['portada'])) ?>" alt="<?= htmlspecialchars($libro['titulo']) ?>" class="portada-img">
                            <?php else: ?>
                                <span class="no-portada">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($libro['titulo']) ?></td>
                        <td><?= htmlspecialchars($libro['autor']) ?></td>
                        <td><a href="?action=edit&id=<?= $libro['id'] ?>" class="action-btn btn-edit">
                                <span class="btn-icon">✏️</span> Editar
                            </a>
                            <form action="index.php?action=delete&id=<?= $libro['id'] ?>" method="POST" style="display:inline;">
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('¿Estás seguro de que quieres borrar este libro?');">
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