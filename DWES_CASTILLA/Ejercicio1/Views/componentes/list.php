<?php
// paso3/views/clientes/form.php
require __DIR__ . '/../header.php'; ?>
<body>

<div class="topbar">
    <h1>Componentes</h1>
    <div>
        <a class="btn" href="index.php">Listado</a>
        <a class="btn" href="index.php?action=add">Añadir componente</a>
    </div>
</div>


  <h2>Listado de componentes</h2>

    <?php if (count($componentes) === 0): ?>
        <p>No hay componentes todavía. Usa "Añadir componente" para crear uno.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Referencia</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($componentes as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['referencia']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['stock']); ?></td>
                        <td class="actions">
                            <a href="index.php?action=edit&id=<?php echo $row['id']; ?>">Editar</a>
                            <a class="btn btn-danger" href="index.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('¿Borrar componente #<?php echo $row['id']; ?>?');">Borrar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>


    
<?php
// paso3/views/clientes/form.php
require __DIR__ . '/../footer.php';
?>