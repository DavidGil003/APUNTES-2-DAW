<?php
// =============================================
// PÁGINA DE ERROR CRÍTICO - VERSIÓN PRO 2025
// =============================================

// Detectamos si estamos en entorno de desarrollo
$isDev = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || str_contains($_SERVER['HTTP_HOST'] ?? '', 'localhost');

// Solo mostramos detalles técnicos en desarrollo
$errorMessage = $isDev ? ($e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine()) : 'Error interno del servidor.';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error • Biblioteca</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Variables globales de tu diseño principal -->
    <style>
        :root {
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --primary: #4f46e5;
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --danger-bg: #fef2f2;
            --danger-border: #fecaca;
            --radius: 12px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--text-main);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1.6;
        }

        .error-card {
            background: var(--bg-card);
            padding: 56px 48px;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            text-align: center;
            max-width: 540px;
            width: 100%;
            border-top: 6px solid var(--danger);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--danger), #f87171, #fca5a5);
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: var(--danger-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 6px solid #fee2e2;
        }

        .icon svg {
            width: 44px;
            height: 44px;
            fill: var(--danger);
        }

        h1 {
            margin: 0 0 16px;
            font-size: 2.6rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.8px;
        }

        p {
            color: var(--text-muted);
            font-size: 1.15rem;
            margin: 0 0 32px;
            line-height: 1.7;
        }

        .tech-info {
            background: var(--danger-bg);
            color: #991b1b;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid var(--danger);
            font-family: 'SF Mono', Monaco, Consolas, monospace;
            font-size: 0.875rem;
            text-align: left;
            margin: 32px 0;
            border: 1px solid var(--danger-border);
            word-break: break-all;
            line-height: 1.6;
            position: relative;
            overflow: hidden;
        }

        .tech-info::before {
            content: "Technical Details";
            position: absolute;
            top: 8px; right: 12px;
            font-size: 0.65rem;
            color: #cf2222;
            opacity: 0.7;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .btn-retry {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 36px;
            background: var(--danger);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
            min-width: 200px;
        }

        .btn-retry:hover {
            background: var(--danger-dark);
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
        }

        .btn-retry:active {
            transform: translateY(-1px);
        }

        .btn-retry svg {
            width: 20px;
            height: 20px;
        }

        .footer-note {
            margin-top: 40px;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {
            .error-card {
                padding: 40px 24px;
                margin: 10px;
            }
            h1 { font-size: 2.2rem; }
            .icon { width: 70px; height: 70px; }
            .icon svg { width: 38px; height: 38px; }
            .btn-retry { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="icon">
            <svg viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
        </div>

        <h1>Conexión Perdida</h1>
        <p>No se ha podido conectar con la base de datos. Es posible que el servidor esté temporalmente fuera de servicio o que haya un problema de configuración.</p>

        <?php if ($isDev): ?>
            <div class="tech-info">
                <?= nl2br(htmlspecialchars($errorMessage)) ?>
            </div>
        <?php else: ?>
            <div class="tech-info" style="opacity: 0.7; font-style: italic;">
                Los detalles del error están ocultos por seguridad.<br>
                Contacta al administrador si el problema persiste.
            </div>
        <?php endif; ?>

        <a href="index.php" class="btn-retry">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
            </svg>
            Volver al inicio
        </a>

        <div class="footer-note">
            Biblioteca • Error crítico del sistema
        </div>
    </div>

</body>
</html>
<?php exit; ?>