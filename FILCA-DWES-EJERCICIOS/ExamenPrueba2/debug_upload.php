<?php
// Script para verificar la configuración de subida de archivos
echo "<h2>Configuración de subida de archivos PHP</h2>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>Configuración</th><th>Valor</th><th>Descripción</th></tr>";

$configs = [
    'upload_max_filesize' => 'Tamaño máximo de archivo que se puede subir',
    'post_max_size' => 'Tamaño máximo de datos POST (debe ser mayor que upload_max_filesize)',
    'max_file_uploads' => 'Número máximo de archivos que se pueden subir simultáneamente',
    'memory_limit' => 'Límite de memoria para el script',
    'max_execution_time' => 'Tiempo máximo de ejecución del script'
];

foreach ($configs as $config => $description) {
    $value = ini_get($config);
    echo "<tr>";
    echo "<td><strong>$config</strong></td>";
    echo "<td>$value</td>";
    echo "<td>$description</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Conversión a bytes:</h3>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . " = " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . " = " . ini_get('post_max_size') . "<br>";

function convertToBytes($value) {
    $value = trim($value);
    $last = strtolower($value[strlen($value)-1]);
    $value = (int) $value;
    switch($last) {
        case 'g':
            $value *= 1024;
        case 'm':
            $value *= 1024;
        case 'k':
            $value *= 1024;
    }
    return $value;
}

echo "<br><strong>En bytes:</strong><br>";
echo "upload_max_filesize: " . convertToBytes(ini_get('upload_max_filesize')) . " bytes<br>";
echo "post_max_size: " . convertToBytes(ini_get('post_max_size')) . " bytes<br>";
echo "2MB = " . (2 * 1024 * 1024) . " bytes<br>";

echo "<h3>¿La configuración permite archivos de 2MB?</h3>";
$uploadMax = convertToBytes(ini_get('upload_max_filesize'));
$postMax = convertToBytes(ini_get('post_max_size'));
$twoMB = 2 * 1024 * 1024;

if ($uploadMax >= $twoMB && $postMax >= $twoMB) {
    echo "<span style='color: green;'>✅ SÍ, la configuración permite archivos de 2MB</span>";
} else {
    echo "<span style='color: red;'>❌ NO, la configuración NO permite archivos de 2MB</span><br>";
    echo "Necesitas aumentar upload_max_filesize y post_max_size en php.ini";
}
?>