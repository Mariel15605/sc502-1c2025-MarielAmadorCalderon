<?php
header("Content-Type: application/json");
require_once "conexion.php";
require_once "sesiones.php";

verificarSesionActiva();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM tareas ORDER BY fecha_creacion DESC";
        $result = $conn->query($sql);
        $tareas = [];

        while ($row = $result->fetch_assoc()) {
            $tareas[] = $row;
        }

        echo json_encode($tareas);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['titulo'], $data['descripcion'])) {
            $titulo = $conn->real_escape_string($data['titulo']);
            $descripcion = $conn->real_escape_string($data['descripcion']);
            $usuario = $conn->real_escape_string($_SESSION['usuario']);

            $sql = "INSERT INTO tareas (titulo, descripcion, estado, fecha_creacion) 
                    VALUES ('$titulo', '$descripcion', 'pendiente', NOW())";

            if ($conn->query($sql)) {
                echo json_encode(['mensaje' => 'insertado']);
            } else {
                echo json_encode(['error' => 'error al insertar']);
            }
        } else {
            echo json_encode(['error' => 'datos incompletos']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>

