<?php
// Script para migrar las rutas de imágenes existentes a rutas dinámicas
require_once "db/Conexion.php";

try {
    $conexion = new Conexion('localhost', 'biblioteca', 'root', '');
    $pdo = $conexion->getPdo();
    
    // Obtener la ruta del proyecto actual
    $scriptName = $_SERVER['SCRIPT_NAME']; // ej: /temp/Ejercicios/ExamenPrueba2/migrar_rutas.php
    $rutaProyecto = dirname($scriptName); // ej: /temp/Ejercicios/ExamenPrueba2
    
    echo "Ruta del proyecto detectada: " . $rutaProyecto . "\n\n";
    
    // Buscar todos los libros con portadas
    $stmt = $pdo->query("SELECT id, portada FROM libros WHERE portada IS NOT NULL AND portada != ''");
    $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($libros)) {
        echo "No hay libros con portadas para migrar.\n";
        exit;
    }
    
    echo "Libros encontrados con portadas: " . count($libros) . "\n\n";
    
    foreach ($libros as $libro) {
        $rutaActual = $libro['portada'];
        
        // Si ya es una ruta completa (empieza con /) no la modificamos
        if (strpos($rutaActual, '/') === 0) {
            echo "Libro ID {$libro['id']}: Ya tiene ruta completa: $rutaActual\n";
            continue;
        }
        
        // Si es una ruta relativa (uploads/archivo.jpg), la convertimos
        if (strpos($rutaActual, 'uploads/') === 0) {
            $nuevaRuta = $rutaProyecto . '/' . $rutaActual;
            
            // Actualizar en la base de datos
            $updateStmt = $pdo->prepare("UPDATE libros SET portada = ? WHERE id = ?");
            $updateStmt->execute([$nuevaRuta, $libro['id']]);
            
            echo "Libro ID {$libro['id']}: Actualizado de '$rutaActual' a '$nuevaRuta'\n";
        } else {
            echo "Libro ID {$libro['id']}: Ruta no reconocida: $rutaActual\n";
        }
    }
    
    echo "\n✅ Migración completada!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>