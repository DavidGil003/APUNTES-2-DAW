<?php
// paso3/views/clientes/form.php
require __DIR__ . '/../header.php';
$action = $action ?? ($_REQUEST['action']??null);
$componentes = $componentes ?? [];
$componente = $componente ?? null;
?>



    <h2><?php echo $action === 'edit' ? 'Editar componente' : 'Añadir componente'; ?></h2>

    <form method="post" action="index.php?action=<?php echo $action === 'edit' ? 'edit' : 'add'; ?>">
        <?php if ($action === 'edit'): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($componente['id']); ?>">
        <?php endif; ?>

        <label>Referencia
            <input type="text" name="referencia" required value="<?php echo htmlspecialchars($componente['referencia'] ?? ''); ?>">
        </label>

        <label>Nombre
            <input type="text" name="nombre" required value="<?php echo htmlspecialchars($componente['nombre'] ?? ''); ?>">
        </label>

        <label>Stock
            <input type="number" name="stock" required min="0" value="<?php echo htmlspecialchars($componente['stock'] ?? 0); ?>">
        </label>

        <button class="btn" type="submit"><?php echo $action === 'edit' ? 'Guardar cambios' : 'Añadir'; ?></button>
        <a href="index.php" style="margin-left:8px">Cancelar</a>
    </form>



  


<?php
// paso3/views/clientes/form.php
require __DIR__ . '/../footer.php';
?>