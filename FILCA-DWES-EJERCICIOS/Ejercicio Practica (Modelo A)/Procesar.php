<?php require_once 'Alumno.php';
function calcularMedia($notas, $comportamiento): float
{
    $mediaNotas = 0;
    $totalNotas = sizeof($notas);

    for ($i = 0; $i < $totalNotas; $i++) {
        $mediaNotas += $notas[$i];
    }

    $mediaNotas /= $totalNotas;
    
    switch ($comportamiento) {
        case "Muy malo":
            $mediaNotas -= 2;
            break;
        case "Malo":
            $mediaNotas -= 1;
            break;
        case "Bueno":
            $mediaNotas += 1;
            break;
        case "Muy bueno":
            $mediaNotas += 2;
            break;
    }

    if ($mediaNotas < 0) {
        $mediaNotas = 0;
    } elseif ($mediaNotas > 10) {
        $mediaNotas = 10;
    }

    return $mediaNotas;
}
if (isset($_POST["nombre"]) && isset($_POST["fechaNacimiento"]) && isset($_POST["notas"]) && isset($_POST["comportamiento"])) {
    $nombreAlumno = $_POST["nombre"];
    $fechaNacimiento = $_POST["fechaNacimiento"];

    $pattern = '/^(([0-9](\.[0-9]+)?|10(\.0+)?)(\|([0-9](\.[0-9]+)?|10(\.0+)?))*)$/';

    if (!preg_match($pattern, $_POST["notas"])) {
        exit('
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Error en los datos</title>
            <style>
                body {
                    min-height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
                    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                    margin: 0;
                }
                .error-container {
                    background: #fff;
                    border-radius: 20px;
                    padding: 40px;
                    text-align: center;
                    max-width: 600px;
                    box-shadow: 0 15px 50px rgba(0,0,0,0.3);
                    animation: fadeIn 0.6s ease-out;
                }
                h1 {
                    font-size: 26px;
                    color: #eb3349;
                    margin-bottom: 20px;
                }
                p {
                    color: #444;
                    font-size: 16px;
                    margin-bottom: 25px;
                    line-height: 1.6;
                }
                .btn-volver {
                    display: inline-block;
                    padding: 14px 24px;
                    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
                    color: white;
                    text-decoration: none;
                    border-radius: 12px;
                    font-weight: bold;
                    transition: all 0.3s ease;
                }
                .btn-volver:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 8px 20px rgba(235, 51, 73, 0.4);
                }
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(-20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            </style>
        </head>
        <body>
            <div class="error-container">
                <h1>❌ Error en las notas</h1>
                <p>Las notas deben estar entre <strong>0 y 10</strong>, separadas por <strong>|</strong>.<br>
                Ejemplo válido: <code>7.5|8|6.25</code></p>
                <a href="javascript:history.back()" class="btn-volver">← Volver al formulario</a>
            </div>
        </body>
        </html>');
    }

    $notas = explode("|", $_POST["notas"]);
    $comportamiento = $_POST["comportamiento"];


    if (isset($_POST["comentarios"])) {
        $comentarios = $_POST["comentarios"];
    }

    $alumno = new Alumno($nombreAlumno, $fechaNacimiento, $notas, $comportamiento);

    $alumno->setComentarios($comentarios);

    $notaMediaAlumno = calcularMedia($notas, $comportamiento);
} else {
    echo "ha habido algun problema a la hora de recoger datos";
} ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Estudiante</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #7c88ea 0%, #8b6bb2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 650px;
            width: 100%;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            color: #7c88ea;
            font-size: 28px;
            margin-bottom: 25px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .status-badge {
            text-align: center;
            padding: 18px 30px;
            border-radius: 16px;
            margin-bottom: 30px;
            font-size: 22px;
            font-weight: bold;
            animation: pulse 2s ease-in-out infinite;
            letter-spacing: 1px;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }
        }

        .aprobado {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            box-shadow: 0 10px 30px rgba(56, 239, 125, 0.3);
        }

        .suspendido {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
            box-shadow: 0 10px 30px rgba(235, 51, 73, 0.3);
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .info-row {
            display: flex;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-icon {
            font-size: 24px;
            min-width: 40px;
        }

        .info-label {
            font-weight: 600;
            color: #7c88ea;
            min-width: 200px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .info-value {
            color: #333;
            font-size: 16px;
            flex: 1;
        }

        .notas-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .nota-badge {
            background: linear-gradient(135deg, #7c88ea 0%, #8b6bb2 100%);
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 3px 10px rgba(124, 136, 234, 0.3);
        }

        .promedio-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 10px 24px;
            border-radius: 14px;
            font-weight: bold;
            font-size: 20px;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.3);
        }

        .comentarios-box {
            background: #f8f9ff;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #7c88ea;
            color: #555;
            font-style: italic;
            line-height: 1.6;
        }

        .btn-volver {
            display: block;
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #7c88ea 0%, #8b6bb2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-volver:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(124, 136, 234, 0.4);
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 24px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-label {
                min-width: auto;
                margin-bottom: 8px;
            }

            .info-icon {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>📊 Resultado del Estudiante</h1>

        <!-- Aprobado o Suspendido dinámico -->
        <div class="status-badge <?php echo ($notaMediaAlumno >= 5) ? 'aprobado' : 'suspendido'; ?>">
            <?php echo ($notaMediaAlumno >= 5) ? "✓ APROBADO" : "✗ SUSPENDIDO"; ?>
        </div>

        <div class="info-card">
            <div class="info-row">
                <div class="info-icon">👤</div>
                <div class="info-label">Nombre</div>
                <div class="info-value"><?php echo $nombreAlumno; ?></div>
            </div>

            <div class="info-row">
                <div class="info-icon">📅</div>
                <div class="info-label">Fecha de Nacimiento</div>
                <div class="info-value"><?php echo $fechaNacimiento; ?></div>
            </div>

            <div class="info-row">
                <div class="info-icon">📝</div>
                <div class="info-label">Notas</div>
                <div class="info-value">
                    <div class="notas-container">
                        <?php foreach ($notas as $nota): ?>
                            <span class="nota-badge"><?php echo $nota; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">⭐</div>
                <div class="info-label">Comportamiento</div>
                <div class="info-value"><?php echo $comportamiento; ?></div>
            </div>

            <div class="info-row">
                <div class="info-icon">📊</div>
                <div class="info-label">Nota Media Final</div>
                <div class="info-value">
                    <span class="promedio-badge"><?php echo number_format($notaMediaAlumno, 2); ?></span>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">💬</div>
                <div class="info-label">Comentarios</div>
                <div class="info-value">
                    <div class="comentarios-box">
                        <?php echo isset($comentarios) ? nl2br(htmlspecialchars($comentarios)) : "Sin comentarios"; ?>
                    </div>
                </div>
            </div>
        </div>

        <a href="javascript:history.back()" class="btn-volver">← Volver al Formulario</a>
    </div>
</body>

</html>