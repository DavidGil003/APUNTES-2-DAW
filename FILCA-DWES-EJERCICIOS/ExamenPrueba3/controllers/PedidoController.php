<?php
require_once __DIR__ . "/../models/PedidoModel.php";
require_once __DIR__ . "/../models/PlatoModel.php";

class PedidoController
{
    private $modeloPedido;
    private $modeloPlato;
    private $basePath;

    public function __construct(PDO $pdo)
    {
        $this->modeloPedido = new PedidoModel($pdo);
        $this->modeloPlato = new PlatoModel($pdo);
        $this->basePath = dirname(__DIR__);
    }

    public function index()
    {
        $pedidosActivos = $this->modeloPedido->getActivos();
        $platos = $this->modeloPlato->getAll();
        $action = 'list';
        $error = null;

        require_once $this->basePath . '/views/layouts/header.php';
        require_once $this->basePath . '/views/pedidos/form.php';
        require_once $this->basePath . '/views/pedidos/activos.php';
        require_once $this->basePath . '/views/layouts/footer.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validaciones
                if (empty($_POST['plato_id']) || empty($_POST['num_mesa']) || empty($_POST['cantidad'])) {
                    throw new Exception("Los campos plato, mesa y cantidad son obligatorios");
                }

                $platoId = (int) $_POST['plato_id'];
                $numMesa = (int) $_POST['num_mesa'];
                $cantidad = (int) $_POST['cantidad'];
                $nombreCliente = trim($_POST['nombre_cliente'] ?? '');

                // Validar rango de mesa
                if ($numMesa < 1 || $numMesa > 20) {
                    throw new Exception("El número de mesa debe estar entre 1 y 20");
                }

                // Validar cantidad
                if ($cantidad < 1 || $cantidad > 10) {
                    throw new Exception("La cantidad debe estar entre 1 y 10");
                }

                // Obtener datos del plato
                $plato = $this->modeloPlato->getByID($platoId);
                if (!$plato) {
                    throw new Exception("El plato seleccionado no existe");
                }

                // Calcular total
                $total = $plato['precio'] * $cantidad;

                $datos = [
                    'plato_id' => $platoId,
                    'num_mesa' => $numMesa,
                    'cantidad' => $cantidad,
                    'nombre_cliente' => !empty($nombreCliente) ? $nombreCliente : null,
                    'fecha_pedido' => date('Y-m-d H:i:s'),
                    'total' => $total
                ];

                $this->modeloPedido->create($datos);

                $_SESSION['mensaje'] = "Pedido registrado correctamente. Total: €" . number_format($total, 2);
                $_SESSION['tipo_mensaje'] = "success";

                header('Location: pedidos.php?action=list');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
                $platos = $this->modeloPlato->getAll();
                $pedidosActivos = $this->modeloPedido->getActivos();

                require_once $this->basePath . '/views/layouts/header.php';
                require_once $this->basePath . '/views/pedidos/form.php';
                require_once $this->basePath . '/views/pedidos/activos.php';
                require_once $this->basePath . '/views/layouts/footer.php';
                return;
            }
        }

        $this->index();
    }

    public function marcarServido()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = (int) ($_POST['id'] ?? 0);
                if (!$id) {
                    throw new Exception("ID de pedido inválido");
                }

                $pedido = $this->modeloPedido->getByID($id);
                if (!$pedido) {
                    throw new Exception("El pedido no existe");
                }

                $this->modeloPedido->marcarServido($id);

                $_SESSION['mensaje'] = "Pedido marcado como servido";
                $_SESSION['tipo_mensaje'] = "success";
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error: " . $e->getMessage();
                $_SESSION['tipo_mensaje'] = "error";
            }
        }

        header('Location: pedidos.php?action=list');
        exit;
    }

    public function finalizados()
    {
        $pedidosFinalizados = $this->modeloPedido->getFinalizados();
        $totalFacturado = $this->modeloPedido->getTotalFacturado();
        $action = 'finalizados';

        require_once $this->basePath . '/views/layouts/header.php';
        require_once $this->basePath . '/views/pedidos/finalizados.php';
        require_once $this->basePath . '/views/layouts/footer.php';
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = (int) ($_POST['id'] ?? 0);
                if (!$id) {
                    throw new Exception("ID de pedido inválido");
                }

                $pedido = $this->modeloPedido->getByID($id);
                if (!$pedido) {
                    throw new Exception("El pedido no existe");
                }

                $this->modeloPedido->delete($id);

                $_SESSION['mensaje'] = "Pedido eliminado correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error: " . $e->getMessage();
                $_SESSION['tipo_mensaje'] = "error";
            }
        }

        header('Location: pedidos.php?action=list');
        exit;
    }
}
