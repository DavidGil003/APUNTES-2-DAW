<?php 
require_once 'Alumno.php';



if(isset($_POST['nombre']) && isset($_POST['fechaNacimiento']) && isset($_POST['notas']) && isset($_POST['comportamiento']) && isset($_POST['comentarios']) ) {


$nombre = $_POST['nombre'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$notas = $_POST['notas'];
$comportamiento = $_POST['comportamiento'];
$comentarios = $_POST['comentarios'];

$alumno = new Alumno($nombre,$fechaNacimiento,$notas,$comportamiento,$comentarios);


$media = $alumno->calcularNota($notas, $comportamiento);
$estado = $alumno->estaSupendido($media);
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado - Calculadora de Notas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            animation: slideIn 0.5s ease;
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
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
            text-align: center;
        }

        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 35px;
            font-size: 14px;
        }

        .resultado {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .info-row {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 700;
            color: #667eea;
            min-width: 160px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-value {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 500;
            flex: 1;
        }

        .media-grande {
            font-size: 48px;
            font-weight: 700;
            text-align: center;
            margin: 20px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .estado-badge {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .aprobado {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }

        .suspendido {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }

        .estado-container {
            text-align: center;
            margin-top: 25px;
            padding: 25px;
            background: white;
            border-radius: 12px;
        }

        .btn-volver {
            display: block;
            margin-top: 30px;
            padding: 15px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            font-size: 16px;
        }

        .btn-volver:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .icon {
            font-size: 64px;
            text-align: center;
            margin-bottom: 20px;
        }

        .comentario-section {
            margin-top: 20px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }

        .comentario-title {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .comentario-text {
            color: #555;
            line-height: 1.6;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if(isset($alumno)): ?>
            <div class="icon">
                <?php echo (strpos($estado, 'aprobado') !== false) ? '🎉' : '📚'; ?>
            </div>
            <h1>Resultado del Cálculo</h1>
            <p class="subtitle">Información del alumno procesada</p>
            
            <div class="resultado">
                <div class="info-row">
                    <div class="info-label">👤 Nombre:</div>
                    <div class="info-value"><?php echo htmlspecialchars($alumno->getNombre()); ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">📅 Fecha Nacimiento:</div>
                    <div class="info-value"><?php echo htmlspecialchars($alumno->getFechaNacimiento()); ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">📝 Notas:</div>
                    <div class="info-value"><?php echo htmlspecialchars($alumno->getNotas()); ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">😊 Comportamiento:</div>
                    <div class="info-value"><?php echo htmlspecialchars(ucfirst(str_replace('_', ' ', $alumno->getComportamiento()))); ?></div>
                </div>

                <div class="estado-container">
                    <div class="media-grande" style="color: <?php echo (strpos($estado, 'aprobado') !== false) ? '#11998e' : '#eb3349'; ?>">
                        <?php echo number_format($media, 1); ?>
                    </div>
                    <span class="estado-badge <?php echo (strpos($estado, 'aprobado') !== false) ? 'aprobado' : 'suspendido'; ?>">
                        <?php echo strtoupper($estado); ?>
                    </span>
                </div>

                <?php if(!empty($alumno->getComentario())): ?>
                <div class="comentario-section">
                    <div class="comentario-title">💬 COMENTARIOS:</div>
                    <div class="comentario-text"><?php echo htmlspecialchars($alumno->getComentario()); ?></div>
                </div>
                <?php endif; ?>
            </div>

            <a href="form.html" class="btn-volver">⬅️ Volver al formulario</a>
        <?php endif; ?>
    </div>
</body>
</html>