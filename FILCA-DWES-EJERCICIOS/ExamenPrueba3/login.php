<?php
session_start();

// Si ya hay sesión activa, redirigir al inicio
if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$usuario_recordado = '';

// Verificar si existe cookie de usuario
if (isset($_COOKIE['usuario_recordado'])) {
    $usuario_recordado = htmlspecialchars($_COOKIE['usuario_recordado']);
}

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    // Credenciales por defecto
    $usuario_correcto = 'camarero';
    $contrasena_correcta = 'rest2025';

    if ($usuario === $usuario_correcto && $contrasena === $contrasena_correcta) {
        // Login exitoso
        $_SESSION['usuario'] = $usuario;
        $_SESSION['login_time'] = time();

        // Si marcó "Recordarme", guardar cookie por 7 días
        if (isset($_POST['recordarme'])) {
            setcookie('usuario_recordado', $usuario, time() + (7 * 24 * 60 * 60), "/");
        } else {
            // Si no marcó, eliminar la cookie si existe
            if (isset($_COOKIE['usuario_recordado'])) {
                setcookie('usuario_recordado', '', time() - 3600, "/");
            }
        }

        header('Location: index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante - Login</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
        }

        .login-box {
            background: var(--bg-card);
            padding: 50px;
            border-radius: var(--radius);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            border: 1px solid var(--border);
        }

        .login-box h1 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 10px;
            font-size: 2rem;
        }

        .login-box p {
            text-align: center;
            color: var(--text-muted);
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px;
            background-color: var(--bg-body);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text-main);
            font-size: 1rem;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin-right: 10px;
        }

        .checkbox-group label {
            margin: 0;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-login:hover {
            background-color: var(--primary-dark);
        }

        .alert-error {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .info-box {
            background-color: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        [data-tema="light"] .login-container {
            background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
        }
    </style>
</head>
<body data-tema="dark">
    <div class="login-container">
        <div class="login-box">
            <h1>🍽️ Restaurante</h1>
            <p>Sistema de Gestión</p>

            <?php if ($error): ?>
                <div class="alert-error">
                    ❌ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" value="<?= $usuario_recordado ?>" required>
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="recordarme" name="recordarme">
                    <label for="recordarme">Recordarme por 7 días</label>
                </div>

                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <div class="info-box">
                <strong>Credenciales de prueba:</strong><br>
                Usuario: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">camarero</code><br>
                Contraseña: <code style="background: rgba(0,0,0,0.2); padding: 2px 6px; border-radius: 3px;">rest2025</code>
            </div>
        </div>
    </div>

    <script>
        // Aplicar tema almacenado
        const tema = localStorage.getItem('tema') || 'dark';
        document.body.setAttribute('data-tema', tema);
    </script>
</body>
</html>
