<?php
session_start();

// Encabezados para permitir solicitudes desde el frontend
header("Access-Control-Allow-Origin: *"); // o especifica dominio en producción
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Conexión a base de datos
require_once '../config/db.php';

// Leer datos JSON del cuerpo de la solicitud
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email'], $data['password'])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios"]);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$password = $data['password']; // sin escape porque se compara tal cual

$sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
$result = $conn->query($sql);

if ($result && $user = $result->fetch_assoc()) {
    if ($password === $user['passw']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        echo json_encode(["success" => true, "message" => "Autenticación exitosa"]);
    } else {
        echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
}

$conn->close();
