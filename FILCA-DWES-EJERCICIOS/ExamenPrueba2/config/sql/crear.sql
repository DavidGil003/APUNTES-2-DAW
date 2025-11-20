-- 1. Creamos la base de datos
CREATE DATABASE IF NOT EXISTS biblioteca;
USE biblioteca;

-- 2. Creamos la tabla 'libros'
CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    portada VARCHAR(255) DEFAULT NULL
    -- 'portada' guardará la ruta (ej: "uploads/foto.jpg"), no la imagen en sí.
);