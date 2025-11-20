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

    // Añade esta función al principio o dentro de tu clase, o llámala en el constructor
    private function gestionarCookieVisita()
    {
        // Si ya existe la cookie, guardamos su valor para mostrarlo luego
        $ultimaVisita = $_COOKIE['ultima_visita'] ?? 'Es tu primera visita hoy';

        // Actualizamos la cookie con la fecha y hora ACTUAL (para la próxima vez)
        // Expira en 1 año
        setcookie('ultima_visita', date('d/m/Y H:i:s'), time() + (86400 * 365), "/");

        return $ultimaVisita;
    }

    public function index()
    {
        $libros = $this->modelo->getAll();

        require_once $this->basePath . '/views/layouts/body.php';
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

                if (isset($_FILES['portada'])) {
                    // 1. Detectar si PHP lo bloqueó por tamaño (Error tipo 1 o 2)
                    if ($_FILES['portada']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['portada']['error'] === UPLOAD_ERR_FORM_SIZE) {
                        throw new Exception("El archivo es demasiado grande (supera el límite del servidor).");
                    }

                    // 2. Si la subida fue correcta (Código 0), entonces procesamos
                    if ($_FILES['portada']['error'] === UPLOAD_ERR_OK) {
                        $datos['portada'] = $this->subirImagen($_FILES['portada']);
                    }
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

        $carpeta_destino = $this->basePath . '/public/uploads/';
        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        $nombre_archivo = uniqid('libro_') . '.' . pathinfo($archivo['name'], PATHINFO_EXTENSION);
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

                if (isset($_FILES['portada'])) {
                    if ($_FILES['portada']['error'] === UPLOAD_ERR_INI_SIZE) {
                        throw new Exception("El archivo es demasiado grande.");
                    }

                    if ($_FILES['portada']['error'] === UPLOAD_ERR_OK) {
                        $rutaFoto = $this->subirImagen($_FILES['portada']);

                        // Eliminar imagen anterior si existe
                        if ($libroActual['portada']) {
                            $rutaViejaCompleta = $this->basePath . '/public/uploads/' . basename($libroActual['portada']);
                            if (file_exists($rutaViejaCompleta)) {
                                unlink($rutaViejaCompleta);
                            }
                        }
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
                $_SESSION['mensaje'] = "Error al guardar: " . $e->getMessage();
                $_SESSION['tipo_mensaje'] = "error";
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
            // Obtener datos del libro antes de eliminarlo
            $libro = $this->modelo->getById($id);

            try {
                // Eliminar imagen si existe
                if ($libro && $libro['portada']) {
                    $rutaImagen = $this->basePath . '/public/uploads/' . basename($libro['portada']);
                    if (file_exists($rutaImagen)) {
                        unlink($rutaImagen);
                    }
                }

                $this->modelo->delete($id);
                $_SESSION['mensaje'] = "Libro eliminado correctamente";
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
