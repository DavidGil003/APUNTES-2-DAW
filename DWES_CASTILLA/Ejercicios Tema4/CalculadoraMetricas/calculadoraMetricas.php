<?php
function calcularMetricas(array $horas) {
    $total_tareas = count($horas);
    $total_horas = array_sum($horas);
    $media_horas = $total_tareas > 0 ? $total_horas / $total_tareas : 0;
    return [
        'total_tareas' => $total_tareas,
        'total_horas' => $total_horas,
        'media_horas' => $media_horas
    ];
}

$horasTareasSprint = [];
$metricas = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['horas'])) {
    $cadena = trim($_POST['horas']);
    $partes = explode(',', $cadena);
    foreach ($partes as $h) {
        $horasTareasSprint[] = (int)trim($h);
    }
    $metricas = calcularMetricas($horasTareasSprint);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Métricas del Sprint</title>
</head>
<body>
    <h2>Métricas del Sprint</h2>
    <?php if ($metricas): ?>
    <ul>
        <li>Total de tareas: <?php echo $metricas['total_tareas']; ?></li>
        <li>Total de horas: <?php echo $metricas['total_horas']; ?></li>
        <li>Media de horas por tarea: <?php echo round($metricas['media_horas'], 2); ?></li>
    </ul>
    <?php else: ?>
    <p>No se han recibido datos válidos.</p>
    <?php endif; ?>
    <a href="calculadoraMetricas.html">Volver a calcular</a>
</body>
</html>