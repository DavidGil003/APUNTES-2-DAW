CREATE DATABASE RestauranteDB;
USE RestauranteDB;

CREATE TABLE platos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) UNIQUE NOT NULL,
    categoria ENUM('Entrante', 'Principal', 'Postre') NOT NULL,
    precio DECIMAL(6,2) NOT NULL,
    descripcion TEXT,
    foto VARCHAR(255) DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plato_id INT NOT NULL,
    num_mesa INT NOT NULL,
    cantidad INT NOT NULL,
    nombre_cliente VARCHAR(100) DEFAULT NULL,
    fecha_pedido DATETIME NOT NULL,
    fecha_entrega DATETIME DEFAULT NULL,
    total DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (plato_id) REFERENCES platos(id) ON DELETE RESTRICT
);