<?php
header('Content-Type: application/json');
require 'conexion.php';
require 'sesiones.php';

verificarSesionActiva();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $query = "SELECT * FROM comentarios ORDER BY id DESC";
        $result = $conexion->query($query);
        $comentarios = [];

        while ($row = $result->fetch_assoc()) {
            $comentarios[] = $row;
        }

        echo json_encode($comentarios);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['tareas_id'], $data['contenido'])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
            exit;
        }

        $tarea_id = intval($data['tareas_id']);
        $contenido = $conexion->real_escape_string($data['contenido']);
        $usuario = $conexion->real_escape_string($_SESSION['usuario']);

        $query = "INSERT INTO comentarios (tareas_id, usuario, contenido, fecha) 
                  VALUES ($tarea_id, '$usuario', '$contenido', NOW())";

        if ($conexion->query($query)) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Error al insertar comentario"]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "ID no especificado"]);
            exit;
        }

        $id = intval($data['id']);
        $conexion->query("DELETE FROM comentarios WHERE id = $id");
        echo json_encode(["success" => true]);
        break;

    default:
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>


