<?php
header('Content-Type: application/json');
require 'conexion.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Obtener todos los comentarios
        $query = "SELECT * FROM tareas_comentarios ORDER BY id DESC";
        $result = $conexion->query($query);
        $comentarios = [];

        while ($row = $result->fetch_assoc()) {
            $comentarios[] = $row;
        }

        echo json_encode($comentarios);
        break;

    case 'POST':
        // Agregar comentario
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['tarea_id'], $data['comentario'])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
            exit;
        }

        $tarea_id = intval($data['tarea_id']);
        $comentario = $conexion->real_escape_string($data['comentario']);

        $query = "INSERT INTO tareas_comentarios (tarea_id, comentario) VALUES ($tarea_id, '$comentario')";
        $conexion->query($query);
        echo json_encode(["success" => true]);
        break;

    case 'DELETE':
        // Eliminar comentario
        parse_str(file_get_contents("php://input"), $data);
        if (!isset($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "ID no especificado"]);
            exit;
        }

        $id = intval($data['id']);
        $conexion->query("DELETE FROM tareas_comentarios WHERE id = $id");
        echo json_encode(["success" => true]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>

