<?php if ($action === 'list'): ?>
    <div style="margin-bottom: 20px;">
        <a class="button btn-success" href="?action=add">
            <span>➕</span> Añadir Cliente
        </a>
    </div>

    <!-- Búsqueda rápida opcional -->
    <div style="margin-bottom: 15px;">
        <input type="text" id="quickSearch" placeholder="🔍 Buscar cliente..." 
               style="max-width: 300px; padding: 8px 12px;">
    </div>

    <?php
    // obtener todos los clientes
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY id DESC");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<?php elseif ($action === 'add'): ?>
    <h2>➕ Agregar Nuevo Cliente</h2>
    <p style="color: #64748b; margin-bottom: 20px;">Complete el formulario para registrar un nuevo cliente en el sistema.</p>
    
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

<?php elseif ($action === 'edit' && $cliente): ?>
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

<?php else: ?>
    <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
        <p style="font-size: 3rem; margin-bottom: 10px;">⚠️</p>
        <p style="color: #64748b; font-size: 1.1rem;">La acción solicitada no está disponible.</p>
        <a href="?action=list" class="button btn-primary" style="margin-top: 20px;">🏠 Volver al listado de clientes</a>
    </div>
<?php endif; ?>