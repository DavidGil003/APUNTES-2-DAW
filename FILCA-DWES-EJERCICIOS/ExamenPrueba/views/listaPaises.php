<?php
?>

<?php if (!empty($paises)): ?>
    <fieldset>
        <legend>Listado paises</legend>

        <?php foreach ($paises as $p): ?>
            <ul class="lista-paises">
                <li><?= htmlspecialchars($p['nombre']) ?></li>
                <li><?= htmlspecialchars($p['capital']) ?></li>
            </ul>
        <?php endforeach; ?>
    </fieldset>
<?php endif; ?>