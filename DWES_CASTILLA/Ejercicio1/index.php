<?php

require_once 'Database/db.php';

$action = $_REQUEST['action'] ?? 'list';

//---Crear(ADD)

if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $referencia = trim($_POST['referencia'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    $stmt = $pdo->prepare("INSERT INTO componentes (referencia,nombre,stock)VALUES (?,?,?)");
    $stmt->execute([$referencia, $nombre, $stock]);

    header('Location: index.php');
    exit;

}


//---Editar (UPDATE)
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) ($_POST['id'] ?? 0);
    $referencia = trim($_POST['referencia'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $stock = trim($_POST['stock'] ?? '');

    $stmt = $pdo->prepare("UPDATE componentes SET referencia = ?, nombre=?,stock=? WHERE id = ?");
    $stmt->execute([$referencia, $nombre, $stock, $id]);

    header('Location: index.php');
    exit;

}


//---Borrar(DELETE)
if ($action === 'delete') {

    $id = (int) ($_GET['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM componentes WHERE id = ?");
        $stmt->execute([$id]);
    }

    header('Location: index.php');
    exit;

}

//--Obtener componente para edición

$componente = null;

if($action === 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] === 'GET'){

    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM componentes WHERE id= ?");
    $stmt ->execute([$id]);

    $componente = $stmt->fetch();

}

// Obtener todos los componentes para mostrar en la lista
$stmt = $pdo->query("SELECT * FROM componentes ORDER BY id DESC");
$componentes = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gestión de Componentes</title>
    <style>
        body{font-family: Arial, Helvetica, sans-serif; max-width:900px;margin:20px auto;padding:0 16px}
        table{width:100%;border-collapse:collapse;margin-top:12px}
        th,td{border:1px solid #ddd;padding:8px;text-align:left}
        th{background:#f5f5f5}
        form{margin-top:12px;padding:12px;border:1px solid #e0e0e0;background:#fafafa}
        label{display:block;margin:6px 0}
        input[type=text], input[type=number]{width:100%;padding:6px;border:1px solid #ccc;border-radius:3px}
        .actions a{margin-right:8px}
        .topbar{display:flex;justify-content:space-between;align-items:center}
        .btn{display:inline-block;padding:6px 10px;background:#0078D4;color:#fff;text-decoration:none;border-radius:4px}
        .btn-danger{background:#d9534f}
    </style>
</head>
<body>

<div class="topbar">
    <h1>Componentes</h1>
    <div>
        <a class="btn" href="index.php">Listado</a>
        <a class="btn" href="index.php?action=add">Añadir componente</a>
    </div>
</div>

<?php if ($action === 'add' || ($action === 'edit' && $componente)): ?>

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

<?php else: ?>

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

<?php endif; ?>

</body>
</html>
