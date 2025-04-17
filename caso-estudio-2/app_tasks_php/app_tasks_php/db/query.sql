CREATE DATABASE caso_estudio;

USE caso_estudio;

-- Crear tabla de usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(200) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    passw VARCHAR(100) NOT NULL,
    create_at DATETIME NOT NULL
);

-- Usuario por defecto
INSERT INTO users (email, first_name, last_name, passw, create_at)
VALUES ('administrador@correo.com', 'Mariel', 'Amador', 'admin1234', NOW());

-- Crear tabla de tareas
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description VARCHAR(200) NOT NULL,
    due_date DATETIME NOT NULL,
    create_at DATETIME NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id)
);

-- Crear tabla de comentarios
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    taskId INT NOT NULL,
    description VARCHAR(500) NOT NULL,
    create_at DATETIME NOT NULL,
    FOREIGN KEY (taskId) REFERENCES tasks(id)
);

