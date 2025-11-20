<?php
require_once __DIR__ . "/../models/LibroModel.php";

class LibroController
{
    private $modelo;
    private $basePath;

    public function __construct(PDO $pdo)
    {
        $this->modelo = new LibroModel($pdo);
        $this->basePath = dirname(__DIR__);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                if (empty($_POST['titulo']) || empty($_POST['autor'])) {
                    throw new Exception("Título y autor son obligatorios");
                }

                $datos = [
                    'titulo' => trim($_POST['titulo']),
                    'autor' => trim($_POST['autor']),
                    'portada' => null
                ];

                if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
                    $datos['portada_ruta'] = $this->subirImagen($_FILES['portada']);
                }

                $this->modelo->create($datos);
                
                $_SESSION['mensaje'] = "Libro guardado correctamente";
                $_SESSION['tipo_mensaje'] = "success";

                header('Location: index.php?action=list');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $action = 'add';
        $libro = null;
        require_once $this->basePath . '/views/Libros/form.php';
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

        $carpeta_destino = $this->basePath . '/uploads/';
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        $nombre_archivo = uniqid('libro_') . '.' . pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $ruta_completa = $carpeta_destino . $nombre_archivo;

        if (!move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
            throw new Exception("Error al subir la imagen");
        }

        // Obtener la ruta relativa desde la raíz del servidor web
        $rutaRelativa = $this->obtenerRutaRelativaWeb() . 'uploads/' . $nombre_archivo;
        return $rutaRelativa;
    }

    private function obtenerRutaRelativaWeb(): string
    {
        // Obtener la ruta del script actual relativa al document root
        $scriptName = $_SERVER['SCRIPT_NAME']; // ej: /temp/Ejercicios/ExamenPrueba2/index.php
        $rutaProyecto = dirname($scriptName); // ej: /temp/Ejercicios/ExamenPrueba2
        return $rutaProyecto . '/';
    }

    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $libroActual = $this->modelo->getById($id);

        if (!$libroActual) {
            header('Location: index.php?action=list'); // Si no existe, fuera
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                if (empty($_POST['titulo']) || empty($_POST['autor'])) {
                    throw new Exception("Título y autor son obligatorios");
                }

                $rutaFoto = $libroActual['portada'];

                if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
                    $rutaFoto = $this->subirImagen($_FILES['portada']);

                    $rutaViejaCompleta = $this->basePath . '/' . $libroActual['portada'];
                    if ($libroActual['portada'] && file_exists($rutaViejaCompleta)) {
                        unlink($rutaViejaCompleta);
                    }
                }

                $datos = [
                    'titulo' => trim($_POST['titulo']),
                    'autor' => trim($_POST['autor']),
                    'portada' => $rutaFoto
                ];

                $this->modelo->update($id, $datos);
                
                $_SESSION['mensaje'] = "Libro actualizado correctamente";
                $_SESSION['tipo_mensaje'] = "success";

                header('Location: index.php?action=list');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $action = 'edit';
        $libro = $libroActual;
        require_once $this->basePath . '/views/Libros/form.php';
    }

    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->modelo->delete($id);
            $_SESSION['mensaje'] = "Libro eliminado correctamente";
            $_SESSION['tipo_mensaje'] = "success";
        }

        header('Location: index.php?action=list');
        exit;
    }
}
