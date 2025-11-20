<?php
require_once __DIR__ . "/../models/PlatoModel.php";

class PlatoController
{
    private $modelo;
    private $basePath;

    public function __construct(PDO $pdo)
    {
        $this->modelo = new PlatoModel($pdo);
        $this->basePath = dirname(__DIR__);
    }

    public function index()
    {
        $platos = $this->modelo->getAll();
        $action = 'list';
        $plato = null;
        $error = null;

        require_once $this->basePath . '/views/layouts/header.php';
        require_once $this->basePath . '/views/platos/form.php';
        require_once $this->basePath . '/views/platos/lista.php';
        require_once $this->basePath . '/views/layouts/footer.php';
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                if (empty($_POST['nombre']) || empty($_POST['categoria']) || empty($_POST['descripcion']) || (int) $_POST['precio'] <= 0) {
                    throw new Exception("Hay algun campo vacío o el precio debe ser mayor a 0");
                }

                if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("Debes seleccionar una imagen");
                }

                $fotoDB = $this->subirImagen($_FILES['foto']);

                $datos = [
                    'nombre' => trim($_POST['nombre']),
                    'categoria' => trim($_POST['categoria']),
                    'precio' => trim($_POST['precio']),
                    'descripcion' => trim($_POST['descripcion']),
                    'foto' => $fotoDB
                ];

                $this->modelo->create($datos);

                $_SESSION['mensaje'] = "Plato guardado correctamente";
                $_SESSION['tipo_mensaje'] = "success";

                header('Location: index.php?action=list');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $action = 'add';
        $plato = null;
        $platos = $this->modelo->getAll();
        require_once $this->basePath . '/views/layouts/body.php';
    }

    private function subirImagen(array $archivo): string
    {
        $tipos_permitidos = ['image/jpeg', 'image/png'];


        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeReal = $finfo->file($archivo['tmp_name']);

        if (!in_array($mimeReal, $tipos_permitidos)) {
            throw new Exception("Solo se permiten imágenes JPG o PNG");
        }

        if ($archivo['size'] > 2 * 1024 * 1024) {
            throw new Exception("La imagen no puede superar los 2MB");
        }

        $carpeta_destino = $this->basePath . '/public/uploads/';
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        $nombre_archivo = uniqid('plato_') . '.' . pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $ruta_completa = $carpeta_destino . $nombre_archivo;

        if (!move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
            throw new Exception("Error al subir la imagen");
        }

        // Devolver solo el nombre del archivo para almacenar en la BD
        return $nombre_archivo;
    }

    public static function obtenerUrlImagen($nombreArchivo): string
    {
        if (empty($nombreArchivo)) {
            return '';
        }


        $scriptName = $_SERVER['SCRIPT_NAME'];
        $rutaProyecto = dirname($scriptName);

        $rutaProyecto = str_replace('\\', '/', $rutaProyecto);

        return $rutaProyecto . '/public/uploads/' . $nombreArchivo;
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $platoActual = $this->modelo->getById($id);

        if (!$platoActual) {
            header('Location: index.php?action=list'); // Si no existe, fuera
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                if (empty($_POST['nombre']) || empty($_POST['categoria']) || empty($_POST['descripcion']) || (int) $_POST['precio'] <= 0) {
                    throw new Exception("Hay algun campo vacío o el precio debe ser mayor a 0");
                }

                $rutaFoto = $platoActual['foto'];

                if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
                    if ($_FILES['foto']['error'] === UPLOAD_ERR_INI_SIZE) {
                        throw new Exception("El archivo es demasiado grande.");
                    }

                    if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                        $rutaFoto = $this->subirImagen($_FILES['foto']);

                        // Eliminar imagen anterior si existe
                        if ($platoActual['foto']) {
                            $rutaViejaCompleta = $this->basePath . '/public/uploads/' . basename($platoActual['foto']);
                            if (file_exists($rutaViejaCompleta)) {
                                unlink($rutaViejaCompleta);
                            }
                        }
                    }
                }

                $datos = [
                    'nombre' => trim($_POST['nombre']),
                    'categoria' => trim($_POST['categoria']),
                    'precio' => trim($_POST['precio']),
                    'descripcion' => trim($_POST['descripcion']),
                    'foto' => $rutaFoto
                ];

                $this->modelo->update($id, $datos);

                $_SESSION['mensaje'] = "Plato actualizado correctamente";
                $_SESSION['tipo_mensaje'] = "success";

                header('Location: index.php?action=list');
                exit;
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error al guardar: " . $e->getMessage();
                $_SESSION['tipo_mensaje'] = "error";
            }
        }

        $action = 'edit';
        $plato = $platoActual;
        $platos = $this->modelo->getAll();
        require_once $this->basePath . '/views/layouts/body.php';
    }

    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtener datos del plato antes de eliminarlo
            $plato = $this->modelo->getById($id);

            try {
                // Eliminar imagen si existe
                if ($plato && $plato['foto']) {
                    $rutaImagen = $this->basePath . '/public/uploads/' . basename($plato['foto']);
                    if (file_exists($rutaImagen)) {
                        unlink($rutaImagen);
                    }
                }

                $this->modelo->delete($id);
                $_SESSION['mensaje'] = "Plato eliminado correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } catch (Exception $e) {
                $_SESSION['mensaje'] = "Error al guardar: " . $e->getMessage();
                $_SESSION['tipo_mensaje'] = "error";
            }
        }

        header('Location: index.php?action=list');
        exit;
    }
}
