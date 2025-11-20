<?php
// Require the model file using a reliable path
require __DIR__ . '/../Models/ComponenteModel.php';

class ComponenteController
{
    private $model;
    private $basePath;

    public function __construct(PDO $pdo)
    {

        $this->model = new ComponenteModel($pdo);
        $this->basePath = dirname(__DIR__);
    }

    public function index()
    {
        $componentes = $this->model->getAll();
        // la vista se encargará de incluir header/footer
        include $this->basePath . '/Views/componentes/list.php';
    }
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [

                'referencia' => trim($_POST['referencia'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'stock' => trim($_POST['stock'] ?? '')

            ];

            $this->model->create($data);

            header('Location: index.php');
            exit;

        }
        $action = 'add';
        $componente = null;
        include $this->basePath . '/Views/componentes/form.php';
    }


    public function update()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = (int) ($_POST['id'] ?? 0);
            $data = [
                'referencia' => trim($_POST['referencia'] ?? ''),
                'nombre' => trim($_POST['nombre'] ?? ''),
                'stock' => trim($_POST['stock'] ?? '')

            ];

            $this->model->update($id, $data);



            header('Location: index.php');
            exit;

        }

        $componente = $this->model->getById($id);
        $action = 'edit';
        include $this->basePath . '/Views/componentes/form.php';
    }


    public function delete()
    {



        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->model->delete($id);
        }
        header('Location: index.php');
        exit;

    }




}


















?>


