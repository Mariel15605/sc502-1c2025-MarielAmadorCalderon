<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once '../config/db.php';

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$userId = $_SESSION['user_id'];
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos"]);
    exit;
}

switch ($method) {
    case 'GET':
        // Obtener comentarios de una tarea
        if (!isset($_GET['taskId'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Se requiere el ID de la tarea"]);
            exit;
        }
        
        $taskId = $conn->real_escape_string($_GET['taskId']);
        
        // Verificar que el usuario tiene acceso a la tarea
        $sql = "SELECT userId FROM tasks WHERE id = '$taskId' AND userId = '$userId'";
        $result = $conn->query($sql);
        
        if (!$result || $result->num_rows === 0) {
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "No tienes acceso a esta tarea"]);
            exit;
        }
        
        // Obtener comentarios
        $sql = "SELECT c.id, c.description, c.create_at, u.first_name, u.last_name 
                FROM comments c
                JOIN users u ON c.userId = u.id
                WHERE c.taskId = '$taskId'
                ORDER BY c.create_at DESC";
        $result = $conn->query($sql);
        
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }
        
        echo json_encode(["success" => true, "comments" => $comments]);
        break;
        
    case 'POST':
        // Crear un nuevo comentario
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['taskId'], $data['description'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Datos incompletos"]);
            exit;
        }
        
        $taskId = $conn->real_escape_string($data['taskId']);
        $description = $conn->real_escape_string($data['description']);
        
        // Verificar que el usuario tiene acceso a la tarea
        $sql = "SELECT userId FROM tasks WHERE id = '$taskId' AND userId = '$userId'";
        $result = $conn->query($sql);
        
        if (!$result || $result->num_rows === 0) {
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "No tienes acceso a esta tarea"]);
            exit;
        }
        
        // Insertar comentario
        $sql = "INSERT INTO comments (taskId, userId, description, create_at) 
                VALUES ('$taskId', '$userId', '$description', NOW())";
        
        if ($conn->query($sql)) {
            echo json_encode(["success" => true, "message" => "Comentario agregado"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al agregar comentario"]);
        }
        break;
        
    case 'DELETE':
        // Eliminar un comentario
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Se requiere el ID del comentario"]);
            exit;
        }
        
        $commentId = $conn->real_escape_string($_GET['id']);
        
        // Verificar que el comentario pertenece al usuario
        $sql = "SELECT userId FROM comments WHERE id = '$commentId' AND userId = '$userId'";
        $result = $conn->query($sql);
        
        if (!$result || $result->num_rows === 0) {
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "No puedes eliminar este comentario"]);
            exit;
        }
        
        // Eliminar comentario
        $sql = "DELETE FROM comments WHERE id = '$commentId'";
        
        if ($conn->query($sql)) {
            echo json_encode(["success" => true, "message" => "Comentario eliminado"]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al eliminar comentario"]);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        break;
}

$conn->close();
?>