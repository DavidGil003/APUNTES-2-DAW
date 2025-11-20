<?php
session_start();

// Verificar que existe sesión activa
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

require_once "config/Conexion.php";
require_once "models/PlatoModel.php";
require_once "models/PedidoModel.php";

$conexion = new Conexion('localhost', 'restaurantedb', 'root', '');
$pdo = $conexion->getPdo();

$modeloPlato = new PlatoModel($pdo);
$modeloPedido = new PedidoModel($pdo);

$tipo = $_GET['tipo'] ?? '';

if ($tipo === 'platos') {
    // Exportar platos con estadísticas
    exportarPlatos($modeloPlato);
} elseif ($tipo === 'pedidos') {
    // Exportar pedidos
    exportarPedidos($modeloPedido);
} else {
    header('Location: index.php');
    exit;
}

function exportarPlatos($modelo)
{
    $estadisticas = $modelo->getEstadisticasPlatos();
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="menu_restaurante.csv"');
    
    // BOM para Excel reconozca UTF-8
    echo "\xEF\xBB\xBF";
    
    $output = fopen('php://output', 'w');
    
    // Encabezados
    fputcsv($output, [
        'ID',
        'Nombre',
        'Categoría',
        'Precio (€)',
        'Total Pedidos',
        'Ingresos Generados (€)'
    ], ';');
    
    // Datos
    foreach ($estadisticas as $plato) {
        fputcsv($output, [
            $plato['id'],
            $plato['nombre'],
            $plato['categoria'],
            number_format($plato['precio'], 2, ',', ''),
            $plato['total_pedidos'],
            number_format($plato['ingresos'], 2, ',', '')
        ], ';');
    }
    
    fclose($output);
    exit;
}

function exportarPedidos($modelo)
{
    $pedidos = $modelo->getAll();
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="pedidos_restaurante.csv"');
    
    // BOM para Excel reconozca UTF-8
    echo "\xEF\xBB\xBF";
    
    $output = fopen('php://output', 'w');
    
    // Encabezados
    fputcsv($output, [
        'ID Pedido',
        'Mesa',
        'Plato ID',
        'Cantidad',
        'Cliente',
        'Total (€)',
        'Fecha Pedido',
        'Fecha Entrega',
        'Estado'
    ], ';');
    
    // Datos
    foreach ($pedidos as $pedido) {
        $estado = $pedido['fecha_entrega'] ? 'Completado' : 'Pendiente';
        $fecha_entrega = $pedido['fecha_entrega'] ? date('d/m/Y H:i', strtotime($pedido['fecha_entrega'])) : '-';
        
        fputcsv($output, [
            $pedido['id'],
            $pedido['num_mesa'],
            $pedido['plato_id'],
            $pedido['cantidad'],
            $pedido['nombre_cliente'] ?? '-',
            number_format($pedido['total'], 2, ',', ''),
            date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])),
            $fecha_entrega,
            $estado
        ], ';');
    }
    
    fclose($output);
    exit;
}
?>
