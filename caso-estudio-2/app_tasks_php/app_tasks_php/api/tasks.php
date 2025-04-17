<?php
session_start();

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$userId = $_SESSION['user_id'];

// Obtener el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Lee el cuerpo de la solicitud 
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        // Obtener todas las tareas del usuario
        $stmt = $conn->prepare("SELECT id, title, description, due_date FROM tasks WHERE userId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($tasks);
        break;

    case 'POST':
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $dueDate = $data['due_date'] ?? '';

        if ($title && $description && $dueDate) {
            $stmt = $conn->prepare("INSERT INTO tasks (userId, title, description, due_date, create_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("isss", $userId, $title, $description, $dueDate);
            $stmt->execute();
            echo json_encode(['success' => true, 'task_id' => $stmt->insert_id]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Campos incompletos']);
        }
        break;

    case 'PUT':
        $id = $data['id'] ?? null;
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $dueDate = $data['due_date'] ?? '';

        if ($id && $title && $description && $dueDate) {
            $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ? AND userId = ?");
            $stmt->bind_param("sssii", $title, $description, $dueDate, $id, $userId);
            $stmt->execute();
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Datos incompletos']);
        }
        break;

    case 'DELETE':
        $id = $data['id'] ?? null;

        if ($id) {
            $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND userId = ?");
            $stmt->bind_param("ii", $id, $userId);
            $stmt->execute();
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID no proporcionado']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
