-- Script para crear la base de datos y tabla de países de la Unión Europea

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS unioneuropea CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE unioneuropea;

-- Crear la tabla paises
CREATE TABLE IF NOT EXISTS paises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    capital VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar algunos países de ejemplo (opcional)
INSERT IGNORE INTO paises (nombre, capital) VALUES
('España', 'Madrid'),
('Francia', 'París'),
('Italia', 'Roma'),
('Alemania', 'Berlín'),
('Portugal', 'Lisboa');