<?php if ($action === 'list'): ?>
    <div style="margin-bottom: 20px;">
        <a class="button btn-success" href="?action=add">
            <span>➕</span> Añadir Cliente
        </a>
    </div>

    <!-- Búsqueda rápida opcional -->
    <div style="margin-bottom: 15px;">
        <input type="text" id="quickSearch" placeholder="🔍 Buscar cliente..." style="max-width: 300px; padding: 8px 12px;">
    </div>

    <?php
    include __DIR__ . '/../views/lista.php';
    ?>

<?php elseif ($action === 'add'): include __DIR__ . '/../views/create.php'; ?>


<?php elseif ($action === 'edit' && $cliente): include __DIR__ . '/../views/editar.php'; ?>
    

<?php else: ?>
    <div
        style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
        <p style="font-size: 3rem; margin-bottom: 10px;">⚠️</p>
        <p style="color: #64748b; font-size: 1.1rem;">La acción solicitada no está disponible.</p>
        <a href="?action=list" class="button btn-primary" style="margin-top: 20px;">🏠 Volver al listado de clientes</a>
    </div>
<?php endif; ?>