<?php

// body.php
?>

<h2>Añadir Nueva Tarea</h2>
<form action="index.php" method="POST" enctype="multipart/form-data" class="task-form">
    <div class="form-group">
        <label for="nombre_tarea">Nombre de la tarea:</label>
        <input type="text" id="nombre_tarea" name="nombre_tarea_usuario" required>
    </div>

    <div class="form-group">
        <label for="descripcion_tarea">Descripción de la tarea:</label>
        <textarea id="descripcion_tarea" name="descripcion_tarea" rows="3"></textarea>
    </div>

    <div class="form-group">
        <label for="archivo_tarea">Archivo adjunto (máximo 5MB):</label>
        <input type="file" id="archivo_tarea" name="archivo_tarea" accept="*/*">
        <small>Tamaño máximo: 5MB</small>
    </div>

    <input type="submit" name="añadir_tarea" value="Añadir tarea" class="btn-primary">
</form>

<hr>

<h2>Tareas Pendientes</h2>
<?php

if (isset($_SESSION['tasks']) && is_array($_SESSION['tasks']) && count($_SESSION['tasks']) > 0) {
    echo '<div class="task-list">';

    foreach ($_SESSION['tasks'] as $index => $task) {
        if ($task instanceof Task) {

            $title = htmlspecialchars($task->getTitle());
            $description = htmlspecialchars($task->getDescription());
            $originalFileName = htmlspecialchars($task->getOriginalFileName());
            $completed = $task->getCompleted();

            $completedClass = $completed ? 'completed' : '';
            $completedText = $completed ? '✅ Completada' : '⏳ Pendiente';


            echo "<div class='task-item $completedClass'>";

            echo "<div class='task-content'>";

            echo "<h3>$title</h3>";

            if (!empty($description)) {

                echo "<p class='task-description'>$description</p>";
            }

            if (!empty($originalFileName)) {

                echo "<p class='task-attachment'>📎 Archivo: $originalFileName</p>";
            }
            echo "</div>";

            echo "<div class='task-actions'>";

            echo "<span class='status-badge'>$completedText</span>";


            echo "<form method='POST' style='display: flex; gap: 10px;'>";
            echo "<input type='hidden' name='tarea_index' value='$index'>";
            if (!$completed) {

                echo "<button type='submit' name='marcar_completada' class='btn-complete'>Marcar como hecho</button>";
            } else {

                echo "<button type='submit' name='desmarcar_completada' class='btn-undo'>Desmarcar</button>";
            }
            echo "</form>";
            echo "</div>";
            echo "</div>";
        }
    }
    echo '</div>';
} else {
    echo '<p>No hay tareas añadidas aún.</p>';
}
?>