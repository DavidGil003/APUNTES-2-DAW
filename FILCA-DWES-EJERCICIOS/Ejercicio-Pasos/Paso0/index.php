<?php
// paso0/index.php
// PASO 0 — Código monolítico: conexión, lógica y vistas en un solo fichero.
// NO incluir header/footer en este paso (se hará a partir del paso1).
// ---------- CONFIGURACIÓN DE LA BD (ajusta si tu usuario/clave difieren) ------
$host = '127.0.0.1';
$db = 'dwes_mvc_ej';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
// ---------- LÓGICA (acción = list | add | edit | delete) ----------
$action = $_REQUEST['action'] ?? 'list';
// ADD: insertar nuevo cliente
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $stmt = $pdo->prepare("INSERT INTO clientes (nombre,email,telefono,direccion)
VALUES (?,?,?,?)");
    $stmt->execute([$nombre, $email, $telefono, $direccion]);
    // PRG (Post-Redirect-Get) sería mejor, pero para simplicidad actualizamos
    $action = 'list';
}
// EDIT: actualizar cliente
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $stmt = $pdo->prepare("UPDATE clientes SET nombre=?, email=?, telefono=?,
direccion=? WHERE id=?");
    $stmt->execute([$nombre, $email, $telefono, $direccion, $id]);
    $action = 'list';
}
// DELETE: borrar cliente (por GET en este ejemplo; en producción usar POST)
if ($action === 'delete') {
    $id = (int) ($_GET['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id=?");
        $stmt->execute([$id]);
    }
    $action = 'list';
}
// Obtener datos para editar (cuando accedemos a ?action=edit&id=...)
$cliente = null;
if (
    $action === 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] ===
    'GET'
) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id=?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}
// ---------- VISTA (HTML mezclado con PHP) ----------
?><!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Paso 0 — Código Monolítico (CRUD Clientes)</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            max-width: 1000px;
            margin: 18px auto;
            padding: 10px
        }

        header {
            background: #f6f8fa;
            padding: 10px;
            border-radius: 6px
        }

        h1 {
            margin: 0
        }

        .note {
            color: #666;
            font-size: 0.95em
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left
        }

        form {
            margin-top: 12px;
            padding: 10px;
            border: 1px solid #eee;
            background: #fafafa
        }

        .actions a {
            margin-right: 6px
        }

        .small {
            font-size: 0.9em;
            color: #666
        }

        a.button {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #bbb;
            text-decoration: none;
            background: #fff
        }
    </style>
</head>

<body>
    <header>
        <h1>Paso 0 — Código Monolítico (CRUD Clientes)</h1>
        <p class="note">Todo en un único fichero: conexión, lógica y vista. No hay
            header/footer separados en este paso.</p>
    </header>
    <?php if ($action === 'list'): ?>
        <p><a class="button" href="?action=add">Añadir cliente</a></p>
        <?php
        // obtener todos los clientes
        $stmt = $pdo->query("SELECT * FROM clientes ORDER BY id DESC");
        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <table>
            <thead>
                <tr>

                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Cr
                        eado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="7">No hay clientes todavía.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c['id']) ?></td>
                            <td><?= htmlspecialchars($c['nombre']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                            <td><?= htmlspecialchars($c['direccion']) ?></td>
                            <td><?= htmlspecialchars($c['creado_at']) ?></td>
                            <td class="actions">
                                <a href="?action=edit&id=<?= $c['id'] ?>">Editar</a>
                                |
                                <a href="?action=delete&id=<?= $c['id'] ?>" onclick="return
confirm('¿Borrar cliente #<?= $c['id'] ?>?')">Borrar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php elseif ($action === 'add'): ?>
        <h2>Añadir cliente</h2>
        <form method="post" action="?action=add">
            <label>Nombre<br><input name="nombre" required></label><br><br>
            <label>Email<br><input name="email" type="email" required></label><br><br>
            <label>Teléfono<br><input name="telefono"></label><br><br>
            <label>Dirección<br><input name="direccion"></label><br><br>
            <button type="submit">Crear</button>
            <a class="button" href="?action=list">Volver</a>
        </form>
    <?php elseif ($action === 'edit' && $cliente): ?>
        <h2>Editar cliente #<?= htmlspecialchars($cliente['id']) ?></h2>
        <form method="post" action="?action=edit">
            <input type="hidden" name="id" value="<?= htmlspecialchars($cliente['id'])
                ?>">
            <label>Nombre<br><input name="nombre" required value="<?=
                htmlspecialchars($cliente['nombre']) ?>"></label><br><br>
            <label>Email<br><input name="email" type="email" required value="<?=
                htmlspecialchars($cliente['email']) ?>"></label><br><br>
            <label>Teléfono<br><input name="telefono" value="<?=
                htmlspecialchars($cliente['telefono']) ?>"></label><br><br>
            <label>Dirección<br><input name="direccion" value="<?=
                htmlspecialchars($cliente['direccion']) ?>"></label><br><br>
            <button type="submit">Guardar</button>
            <a class="button" href="?action=list">Volver</a>
        </form>
    <?php else: ?>
        <p>Acción no reconocida. <a href="?action=list">Volver a la lista</a></p>
    <?php endif; ?>
    <hr>
    <p class="small">Estructura de este paso:<br><code>paso0/ index.php (todo en un
solo fichero)</code></p>
</body>

</html>