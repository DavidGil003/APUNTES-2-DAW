<?php
require_once __DIR__ . "/../models/ClienteModel.php";

class ClienteController
{
    private $model;
    private $basePath;

    public function __construct(PDO $pdo)
    {
        $this->model = new ClienteModel($pdo);
        $this->basePath = dirname(__DIR__);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            $this->model->create($datos);

            header('Location: index.php?action=list');
            exit;
        }

        $action = 'add';
        $cliente = null;
        include $this->basePath . '/views/clientes/form.php';
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'email' => $_POST['email'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'direccion' => $_POST['direccion'] ?? ''
            ];

            $this->model->update($id, $datos);

            header('Location: index.php?action=list');
            exit;
        }

        $cliente = $this->model->getById($id);
        $action = 'edit';
        include $this->basePath . '/views/clientes/form.php';
    }

    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php?action=list');
        exit;

    }
}
?>