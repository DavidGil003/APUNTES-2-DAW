<?php
?>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th class="sortable">ID</th>
                <th class="sortable">Nombre</th>
                <th class="sortable">Email</th>
                <th class="sortable">Teléfono</th>
                <th class="sortable">Dirección</th>
                <th class="sortable">Creado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clientes)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                        📋 No hay clientes registrados en el sistema.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($c['id']) ?></strong></td>
                        <td><?= htmlspecialchars($c['nombre']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td><?= htmlspecialchars($c['direccion']) ?></td>
                        <td><span class="badge"><?= htmlspecialchars($c['creado_at']) ?></span></td>
                        <td class="actions">
                            <a href="?action=edit&id=<?= $c['id'] ?>" class="action-btn btn-edit">
                                <span class="btn-icon">✏️</span> Editar
                            </a>
                            <a href="?action=delete&id=<?= $c['id'] ?>" class="action-btn btn-delete">
                                <span class="btn-icon">🗑️</span> Borrar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>