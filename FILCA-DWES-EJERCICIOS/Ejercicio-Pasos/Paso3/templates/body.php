<?php if ($action === 'list'): ?>
    <?php
    include __DIR__ . '/../views/lista.php';
    ?>

<?php elseif ($action === 'add'): include __DIR__ . '/../views/clientes/form.php'; ?>

<?php elseif ($action === 'edit' && $cliente): include __DIR__ . '/../views/clientes/form.php'; ?>
    

<?php else: ?>
    <div
        style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
        <p style="font-size: 3rem; margin-bottom: 10px;">⚠️</p>
        <p style="color: #64748b; font-size: 1.1rem;">La acción solicitada no está disponible.</p>
        <a href="?action=list" class="button btn-primary" style="margin-top: 20px;">🏠 Volver al listado de clientes</a>
    </div>
<?php endif; ?>