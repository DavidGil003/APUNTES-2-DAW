<?php
// Verificar que existe sesión activa
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

// Procesar logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante - Pedidos Finalizados</title>
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div>
                    <h1>Historial de Pedidos</h1>
                </div>
                <div class="header-actions">
                    <span class="user-info">👤 <?= htmlspecialchars($_SESSION['usuario'] ?? 'Usuario') ?></span>
                    <a href="?logout=1" class="btn btn-logout">🚪 Cerrar Sesión</a>
                    <a href="pedidos.php" class="btn btn-secondary">📋 Pedidos Activos</a>
                    <a href="index.php" class="btn btn-secondary">📋 Ver Platos</a>
                </div>
            </div>
        </header>

        <?php if (!empty($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= htmlspecialchars($_SESSION['tipo_mensaje'] ?? 'info') ?>">
                <?= ($_SESSION['tipo_mensaje'] == 'error') ? '❌' : '✅' ?>
                <?= htmlspecialchars($_SESSION['mensaje']) ?>
            </div>
            <?php
            unset($_SESSION['mensaje']);
            unset($_SESSION['tipo_mensaje']);
            ?>
        <?php endif; ?>

        <div class="table-container">
            <h2>📊 Pedidos Completados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mesa</th>
                        <th>Plato</th>
                        <th>Cantidad</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Registrado</th>
                        <th>Servido</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pedidosFinalizados)): ?>
                        <tr>
                            <td colspan="8" class="empty-message">
                                No hay pedidos completados
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pedidosFinalizados as $pedido): ?>
                            <tr>
                                <td><strong>#<?= $pedido['id'] ?></strong></td>
                                <td>Mesa <?= $pedido['num_mesa'] ?></td>
                                <td><?= htmlspecialchars($pedido['plato_id']) ?></td>
                                <td><?= $pedido['cantidad'] ?></td>
                                <td><?= $pedido['nombre_cliente'] ? htmlspecialchars($pedido['nombre_cliente']) : '-' ?></td>
                                <td><strong>€<?= number_format($pedido['total'], 2) ?></strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_entrega'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (!empty($pedidosFinalizados)): ?>
                <div style="margin-top: 20px; padding: 20px; background-color: var(--bg-body); border-radius: 8px;">
                    <h3 style="margin-top: 0;">💰 Resumen Financiero</h3>
                    <p><strong>Total Facturado:</strong> €<?= number_format($totalFacturado, 2) ?></p>
                    <p><strong>Número de Pedidos:</strong> <?= count($pedidosFinalizados) ?></p>
                    <p><strong>Ticket Promedio:</strong> €<?= number_format($totalFacturado / count($pedidosFinalizados), 2) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Restaurante. Todos los derechos reservados.</p>
    </footer>

    <script>
        const tema = localStorage.getItem('tema') || 'dark';
        document.body.setAttribute('data-tema', tema);
    </script>
</body>
</html>
