CREATE DATABASE control_prestamos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE control_prestamos;

CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipo VARCHAR(150) NOT NULL,
    serial VARCHAR(100) NULL,
    tipo_equipo VARCHAR(50) NULL, 
    aprendiz VARCHAR(150) NOT NULL,
    ficha VARCHAR(50) NULL,
    instructor VARCHAR(150) NULL,  
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion DATE NULL,
    devuelto INT NOT NULL DEFAULT 0, 
    observaciones VARCHAR(200) NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);